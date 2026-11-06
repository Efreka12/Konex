import re
import shutil
from docx import Document
from docx.oxml.ns import qn

path = r"C:\Users\nico1\Downloads\Konex-Tarea-IEEE-FINAL.docx"
doc = Document(path)

print("SECTIONS", len(doc.sections))
for i, s in enumerate(doc.sections):
    cols = s._sectPr.find(qn("w:cols"))
    n = cols.get(qn("w:num")) if cols is not None else "1"
    print(f"  sec{i} cols={n} margins={s.left_margin.inches:.2f}in page={s.page_width.inches:.2f}x{s.page_height.inches:.2f}")

text = "\n".join(p.text for p in doc.paragraphs)
print("EDDY", "Eddy Santiago Vargas Tique" in text)
print("NICOLAS", "Nicolás Esteban Martínez González" in text)
print("DAMIAN", "Damián Jesús Barbosa Álvarez" in text)
print("FERIA", "Álvaro José Feria Rodríguez" in text)
print("PALENCIA", "Jeniffer Julieth Palencia Quezada" in text)
print("I JUST", "I. JUSTIFICACIÓN" in text)
print("II OBJ", "II. OBJETIVOS" in text)
print("III ANT", "III. ANTECEDENTES" in text)
print("IV MAR", "IV. MARCOS DE REFERENCIA" in text)
print("V REF", "V. REFERENCIAS" in text)
print("A intl", "A. Antecedentes internacionales" in text)
print("B dept", "A. Antecedentes departamentales" in text or "B. Antecedentes departamentales" in text)
print("C nac", "C. Antecedentes nacionales" in text)
print("A admin", "A. Marco administrativo" in text)
print("B teor", "B. Marco teórico" in text)
print("C legal", "C. Marco legal" in text)
print("D conc", "D. Marco conceptual" in text)

refs = [p.text.strip() for p in doc.paragraphs if re.match(r"^\[\d+\]", p.text.strip())]
print("LISTA REFS", len(refs), "first", refs[0][:80] if refs else None, "last", refs[-1][:80] if refs else None)
print("NUMS", [re.match(r"^\[(\d+)\]", r).group(1) for r in refs[:3]], "...", [re.match(r"^\[(\d+)\]", r).group(1) for r in refs[-3:]])

# first appearance order excluding reference list
seen = []
for p in doc.paragraphs:
    t = p.text.strip()
    if re.match(r"^\[\d+\] ", t) and ("vol." in t or "Ley " in t or "En línea" in t or "Constitución" in t or "Decreto" in t or "Decisión" in t or "UNESCO" in t or "OECD" in t or "Ministerio" in t or "Universidad" in t or "SENA" in t or "Superintendencia" in t or "ITFIP" in t or "Ortiz" in t or "Presidencia" in t or "Congreso" in t or "Comisión" in t or "República" in t or "Institución" in t or "Servicio" in t):
        continue
    if t.startswith("[") and re.match(r"^\[\d+\] ", t):
        continue
    for n in [int(x) for x in re.findall(r"\[(\d+)\]", t)]:
        if n not in seen and n <= 47:
            seen.append(n)
print("FIRST APPEARANCE", seen[:15], "...")
print("LEN", len(seen), "CONSECUTIVA", seen == list(range(1, 48)))

# antecedent counts: paragraphs under those sections - count INTL/DEPT/NAC by unique content
intl = text.split("A. Antecedentes internacionales")[1].split("B. Antecedentes departamentales")[0]
dept = text.split("B. Antecedentes departamentales")[1].split("C. Antecedentes nacionales")[0]
nac = text.split("C. Antecedentes nacionales")[1].split("IV. MARCOS")[0]
print("INTL paras approx", intl.count("\n") )
print("chars intl/dept/nac", len(intl), len(dept), len(nac))

shutil.copy2(path, r"C:\Users\nico1\Documents\Konex-Tarea-IEEE-FINAL.docx")
shutil.copy2(
    r"C:\Users\nico1\Downloads\Konex-Tarea-IEEE-FINAL-informe.txt",
    r"C:\Users\nico1\Documents\Konex-Tarea-IEEE-FINAL-informe.txt",
)
print("copied")
