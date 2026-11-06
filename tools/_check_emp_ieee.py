import re
import shutil
from docx import Document
from docx.oxml.ns import qn

path = r"C:\Users\nico1\Downloads\Konex_Proyecto_Emprendimiento_IEEE.docx"
doc = Document(path)
print("SECTIONS", len(doc.sections))
for i, s in enumerate(doc.sections):
    cols = s._sectPr.find(qn("w:cols"))
    n = cols.get(qn("w:num")) if cols is not None else "1"
    print(f"  sec{i} cols={n}")

text = "\n".join(p.text for p in doc.paragraphs)
for label in [
    "Eddy Santiago Vargas Tique",
    "I. INTRODUCCIÓN",
    "II. JUSTIFICACIÓN",
    "III. OBJETIVOS",
    "IV. INFORMACIÓN GENERAL",
    "V. PRODUCTO O SERVICIO",
    "VI. ESTRATEGIA DE MARKETING DIGITAL",
    "VII. ANÁLISIS ECONÓMICO",
    "VIII. CONCLUSIONES",
    "IX. REFERENCIAS",
    "X. ANEXOS",
    "A. Objetivo general",
    "A. Nombre del emprendimiento",
    "A. Descripción",
    "A. Público objetivo",
    "A. Costos",
]:
    print(label, label in text)

print("APA PORTADA", "INSTITUCIÓN UNIVERSITARIA DE EL ESPINAL" in text and text.strip().startswith("INSTITUCIÓN"))
print("starts", doc.paragraphs[0].text[:80])

seen = []
for p in doc.paragraphs:
    t = p.text.strip()
    if re.match(r"^\[\d+\] ", t):
        continue
    for n in [int(x) for x in re.findall(r"\[(\d+)\]", t)]:
        if n not in seen:
            seen.append(n)
print("CITES FIRST", seen)
print("CONSEC", seen == list(range(1, len(seen) + 1)))
refs = [p.text for p in doc.paragraphs if re.match(r"^\[\d+\]", p.text.strip())]
print("N REFS", len(refs))
shutil.copy2(path, r"C:\Users\nico1\Documents\Konex_Proyecto_Emprendimiento_IEEE.docx")
print("copied")
