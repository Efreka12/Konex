import re
import shutil
from docx import Document

path = r"C:\Users\nico1\Downloads\Konex_Proyecto_Emprendimiento_IEEE.docx"
doc = Document(path)
seen = []
headings = []
print("ENCABEZADOS:")
for p in doc.paragraphs:
    text = p.text.strip()
    if not text:
        continue
    if (
        text.startswith("CAPÍTULO")
        or text.startswith("CIERRE")
        or re.match(r"^\d+\. ", text)
        or re.match(r"^\d+\.\d+\. ", text)
    ):
        headings.append(text)
        print(" ", text[:90])
    nums = [int(n) for n in re.findall(r"\[(\d+)\]", text)]
    if re.match(r"^\[\d+\] ", text):
        continue
    for n in nums:
        if n not in seen:
            seen.append(n)
print()
print("SECUENCIA DE CITAS:", seen)
print("CONSECUTIVA:", seen == list(range(1, len(seen) + 1)))
print("N REFS:", sum(1 for p in doc.paragraphs if re.match(r"^\[\d+\]", p.text.strip())))
print("TABLAS:", len(doc.tables))
print("PARRAFOS:", len(doc.paragraphs))
shutil.copy2(path, r"C:\Users\nico1\Documents\Konex_Proyecto_Emprendimiento_IEEE.docx")
print("copied")
