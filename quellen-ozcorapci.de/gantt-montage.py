#!/usr/bin/env python3
"""Erzeugt die Bilder ozcorapci.de/assets/img/strip-projektplan.jpg (Schwarz/Gelb)
und strip-projektplan-petrol.jpg (Petrol/Messing): Gantt-Diagramm (SVG) wird
perspektivisch auf das Display eines Laptop-Fotos montiert.

Voraussetzungen: Python 3 mit Pillow und numpy, Chromium (SVG -> PNG), curl.
Foto: Unsplash photo-1498050108023-c5249f4df085 (Unsplash-Lizenz).

Ablauf:
  1. SVG zu PNG (1600x1000) rendern, z. B.
     chromium --headless --window-size=1600,1200 --screenshot=gantt_light.png <html mit <img src=gantt_light.svg style="display:block">>
     (Fenster HOEHER als das Bild, sonst entsteht unten ein weisser Streifen)
  2. python3 gantt-montage.py
"""
import subprocess
from PIL import Image, ImageFilter, ImageDraw, ImageEnhance
import numpy as np

PHOTO_URL = "https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=1400&q=80"
PHOTO = "laptop.jpg"
# Eckpunkte der Display-Flaeche im Foto (1400x932): oben links, oben rechts, unten rechts, unten links
DST = [(486, 338), (968, 346), (946, 677), (452, 607)]
CROP = (350, 0, 1050, 932)  # Hochformat 3:4
JOBS = (("gantt_light.png", "strip-projektplan-petrol.jpg", 0.92),
        ("gantt_dark.png", "strip-projektplan.jpg", 1.0))

def coeffs(pb, pa):
    A, B = [], []
    for p1, p2 in zip(pa, pb):
        A.append([p1[0], p1[1], 1, 0, 0, 0, -p2[0]*p1[0], -p2[0]*p1[1]]); B.append(p2[0])
        A.append([0, 0, 0, p1[0], p1[1], 1, -p2[1]*p1[0], -p2[1]*p1[1]]); B.append(p2[1])
    return np.linalg.solve(np.array(A, float), np.array(B, float)).tolist()

subprocess.run(["curl", "-s", "-A", "Mozilla/5.0", "-o", PHOTO, PHOTO_URL], check=True)
base = Image.open(PHOTO).convert("RGB")
for png, out, bright in JOBS:
    g = Image.open(png).convert("RGB")
    src = [(0, 0), (g.width, 0), (g.width, g.height), (0, g.height)]
    warp = g.transform(base.size, Image.PERSPECTIVE, coeffs(src, DST), Image.BICUBIC).filter(ImageFilter.GaussianBlur(0.8))
    warp = ImageEnhance.Brightness(warp).enhance(bright)
    m = Image.new("L", base.size, 0); ImageDraw.Draw(m).polygon(DST, fill=255); m = m.filter(ImageFilter.GaussianBlur(1.1))
    o = Image.composite(warp, base, m)
    gl = Image.new("L", base.size, 0)
    ImageDraw.Draw(gl).polygon([DST[0], DST[1], (DST[1][0], DST[1][1] + 90), (DST[0][0], DST[0][1] + 130)], fill=28)
    gl = gl.filter(ImageFilter.GaussianBlur(22))
    o = Image.composite(Image.new("RGB", base.size, (255, 255, 255)), o, Image.composite(gl, Image.new("L", base.size, 0), m))
    o.crop(CROP).resize((900, 1199), Image.LANCZOS).save(out, quality=84)
    print("geschrieben:", out)
