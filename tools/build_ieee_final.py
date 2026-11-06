# -*- coding: utf-8 -*-
"""Artículo IEEE FINAL: dos columnas, romanos, 47 referencias, 40 antecedentes."""

from docx import Document
from docx.enum.section import WD_SECTION
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Inches, Pt, RGBColor, Twips, Emu

OUT = r"C:\Users\nico1\Downloads\Konex-Tarea-IEEE-FINAL.docx"
REPORT = r"C:\Users\nico1\Downloads\Konex-Tarea-IEEE-FINAL-informe.txt"

# old citation number -> new (order of first appearance)
MAP = {
    1: 1, 5: 2, 6: 3, 7: 4, 8: 5, 9: 6, 10: 7,
    3: 8, 4: 9, 11: 10, 12: 11, 13: 12, 14: 13,
    15: 14, 16: 15, 17: 16, 18: 17, 19: 18, 20: 19,
    21: 20, 22: 21, 23: 22, 24: 23,
    36: 24, 37: 25, 38: 26, 39: 27, 40: 28,
    2: 29, 25: 30, 26: 31, 27: 32, 28: 33, 29: 34, 30: 35,
    31: 36, 32: 37, 33: 38, 34: 39, 35: 40,
    41: 41, 42: 42, 43: 43, 44: 44, 45: 45, 46: 46, 47: 47,
}


def cit(text):
    for old in sorted(MAP.keys(), reverse=True):
        text = text.replace(f"[{old}]", f"§{MAP[old]}§")
    return text.replace("§", "").replace("§", "") if False else text.replace("§", "[TEMP").replace("[TEMP", "[")  # noqa


def C(text):
    for old in sorted(MAP.keys(), reverse=True):
        text = text.replace(f"[{old}]", f"{{{{R{MAP[old]}}}}}")
    for n in range(1, 48):
        text = text.replace(f"{{{{R{n}}}}}", f"[{n}]")
    return text


def font(run, size=10, bold=False, italic=False):
    run.font.name = "Times New Roman"
    run._element.rPr.rFonts.set(qn("w:eastAsia"), "Times New Roman")
    run.font.size = Pt(size)
    run.bold = bold
    run.italic = italic
    run.font.color.rgb = RGBColor(0, 0, 0)


def space(p, before=0, after=6, first=0):
    p.paragraph_format.space_before = Pt(before)
    p.paragraph_format.space_after = Pt(after)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.SINGLE
    if first:
        p.paragraph_format.first_line_indent = Inches(first)


def set_two_columns(section, space_twips=360):
    sect_pr = section._sectPr
    cols = sect_pr.find(qn("w:cols"))
    if cols is None:
        cols = OxmlElement("w:cols")
        sect_pr.append(cols)
    cols.set(qn("w:num"), "2")
    cols.set(qn("w:space"), str(space_twips))
    cols.set(qn("w:equalWidth"), "1")


def page_number_footer(section):
    footer = section.footer
    footer.is_linked_to_previous = False
    p = footer.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run()
    font(run, 9)
    begin = OxmlElement("w:fldChar")
    begin.set(qn("w:fldCharType"), "begin")
    instr = OxmlElement("w:instrText")
    instr.set(qn("xml:space"), "preserve")
    instr.text = " PAGE "
    end = OxmlElement("w:fldChar")
    end.set(qn("w:fldCharType"), "end")
    run._r.append(begin)
    run._r.append(instr)
    run._r.append(end)


def para(doc, text, size=10, bold=False, italic=False, align="justify", before=0, after=6, first=0.17):
    p = doc.add_paragraph()
    p.alignment = {
        "justify": WD_ALIGN_PARAGRAPH.JUSTIFY,
        "center": WD_ALIGN_PARAGRAPH.CENTER,
        "left": WD_ALIGN_PARAGRAPH.LEFT,
    }[align]
    space(p, before, after, first if align == "justify" else 0)
    run = p.add_run(text)
    font(run, size, bold, italic)
    return p


def h_roman(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 10, 8, 0)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    font(run, 10, True, False)


def h_letter(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 8, 4, 0)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    font(run, 10, True, True)


def h_concept(doc, title, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(p, 2, 6, 0.17)
    r1 = p.add_run(title)
    font(r1, 10, False, True)
    r2 = p.add_run(" " + text)
    font(r2, 10)


def ref_item(doc, n, parts):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.left_indent = Inches(0.2)
    p.paragraph_format.first_line_indent = Inches(-0.2)
    space(p, 0, 4, 0)
    first = True
    for text, italic in parts:
        prefix = f"[{n}] " if first else ""
        run = p.add_run(prefix + text)
        font(run, 8, False, italic)
        first = False


# Original references [1]..[47] as parts (authors, italic title/journal, rest)
RAW_REFS = {
    1: [('G. A. Espinel Rubio, C. A. Hernández Suárez y R. Prada Núñez, "WhatsApp vs. campus virtual institucional en tiempos de covid-19. Percepción de los estudiantes de comunicación social," ', False), ("Rev. Boletín Redipe", True), (", vol. 10, no. 10, pp. 519-533, oct. 2021.", False)],
    2: [('M. M. Echeverri Torres y R. Manjarrés-Betancur, "Asistente virtual académico utilizando tecnologías cognitivas de procesamiento de lenguaje natural," ', False), ("Rev. Politécnica", True), (", vol. 16, no. 31, pp. 85-96, 2020.", False)],
    3: [('C. Hodges, S. Moore, B. Lockee, T. Trust y A. Bond, "The difference between emergency remote teaching and online learning," ', False), ("Educause Review", True), (", 27 mar. 2020. [En línea]. Disponible: https://er.educause.edu/", False)],
    4: [('S. Dhawan, "Online learning: A panacea in the time of COVID-19 crisis," ', False), ("J. Educ. Technol. Syst.", True), (", vol. 49, no. 1, pp. 5-22, 2020.", False)],
    5: [('M. del P. García-Chitiva, "Aprendizaje colaborativo, mediado por internet, en procesos de educación superior," ', False), ("Rev. Electrón. Educare", True), (", vol. 25, no. 2, pp. 1-19, 2021.", False)],
    6: [('D. Turnbull, R. Chugh y J. Luck, "Learning management systems: A review of the research published in the last 40 years," ', False), ("J. Comput. Educ.", True), (", 2021.", False)],
    7: [('R. S. Al-Maroof, S. A. Salloum, A. E. Hassanien y K. Shaalan, "Fear from COVID-19 and technology adoption: The impact of Google Meet during coronavirus pandemic," ', False), ("Interact. Learn. Environ.", True), (", 2020.", False)],
    8: [('J. Q. Pérez, T. Daradoumis y J. M. Marquès Puig, "Rediscovering the use of chatbots in education: A systematic literature review," ', False), ("Comput. Appl. Eng. Educ.", True), (", vol. 28, no. 6, pp. 1549-1565, 2020.", False)],
    9: [('C. W. Okonkwo y A. Ade-Ibijola, "Chatbots applications in education: A systematic review," ', False), ("Comput. Educ.: Artif. Intell.", True), (", vol. 2, p. 100033, 2021.", False)],
    10: [("Congreso de la República de Colombia, Ley 1581 de 2012, Por la cual se dictan disposiciones generales para la protección de datos personales, 17 oct. 2012, Diario Oficial No. 48.587. [En línea]. Disponible: http://www.secretariasenado.gov.co/", False)],
    11: [('C. Rapanta, L. Botturi, P. Goodyear, L. Guàrdia y M. Koole, "Online university teaching during and after the Covid-19 crisis: Refocusing teacher presence and learning activity," ', False), ("Postdigital Sci. Educ.", True), (", vol. 2, pp. 923-945, 2020.", False)],
    12: [('M. Bond, S. Bedenlier, V. I. Marín y M. Händel, "Emergency remote teaching in higher education: Mapping the first global online semester," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 18, art. 50, 2021.", False)],
    13: [('O. B. Adedoyin y E. Soykan, "Covid-19 pandemic and online learning: The challenges and opportunities," ', False), ("Interact. Learn. Environ.", True), (", 2020.", False)],
    14: [('P. Smutny y P. Schreiberova, "Chatbots for learning: A review of educational chatbots for the Facebook Messenger," ', False), ("Comput. Educ.", True), (", vol. 151, p. 103862, 2020.", False)],
    15: [('L. Labadze, M. Grigolia y L. Machaidze, "Role of AI chatbots in education: Systematic literature review," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 20, art. 56, 2023.", False)],
    16: [('H. Crompton y D. Burke, "Artificial intelligence in higher education: The state of the field," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 20, art. 22, 2023.", False)],
    17: [('E. Kasneci et al., "ChatGPT for good? On opportunities and challenges of large language models for education," ', False), ("Learn. Individ. Differ.", True), (", vol. 103, p. 102274, 2023.", False)],
    18: [("UNESCO, ", False), ("Guidance for generative AI in education and research", True), (". París, Francia: UNESCO, 2023.", False)],
    19: [("OECD, ", False), ("OECD Digital Education Outlook 2021: Pushing the Frontiers with Artificial Intelligence, Blockchain and Robots", True), (". París, Francia: OECD Publishing, 2021.", False)],
    20: [('H. Fiock, "Designing a community of inquiry in online courses," ', False), ("Int. Rev. Res. Open Distrib. Learn.", True), (", vol. 21, no. 1, pp. 135-153, 2020.", False)],
    21: [('F. Martin, T. Sun y C. D. Westine, "A systematic review of research on online teaching and learning from 2009 to 2018," ', False), ("Comput. Educ.", True), (", vol. 159, p. 104009, 2020.", False)],
    22: [('R. Zhang, D. Zou y G. Cheng, "A review of chatbot-assisted learning: Pedagogical approaches, implementations, factors leading to effectiveness, theories, and future directions," ', False), ("Interact. Learn. Environ.", True), (", vol. 32, no. 8, pp. 4529-4557, 2023.", False)],
    23: [('T. K. F. Chiu, Q. Xia, X. Zhou, C. S. Chai y M. Cheng, "Systematic literature review on opportunities, challenges, and future research recommendations of artificial intelligence in education," ', False), ("Comput. Educ.: Artif. Intell.", True), (", vol. 4, p. 100118, 2023.", False)],
    24: [('A. Tlili et al., "What if the devil is my guardian angel: ChatGPT as a case study of using chatbots in education," ', False), ("Smart Learn. Environ.", True), (", vol. 10, art. 15, 2023.", False)],
    25: [('M. G. Veytia Bucheli y F. A. Bastidas Terán, "WhatsApp como recurso para el trabajo grupal en estudiantes universitarios," ', False), ("Apertura", True), (", vol. 12, no. 2, pp. 74-93, 2020.", False)],
    26: [('N. A. Guiñez-Cabrera y K. Mansilla-Obando, "WhatsApp Web con fines académicos en tiempos de la covid-19," ', False), ("Apertura", True), (", vol. 13, no. 2, 2021.", False)],
    27: [('M. Villamizar Carvajal y P. A. Viviescas Jaimes, "Plataforma web colaborativa de aprendizaje académico," trabajo de grado, Universidad Autónoma de Bucaramanga, Bucaramanga, Colombia, 2024. [En línea]. Disponible: http://hdl.handle.net/20.500.12749/29817', False)],
    28: [('M. Mejia-Lobo, R. Gil-Fernández y D. Calderón-Garrido, "Evaluación del impacto de una red social académica diseñada para promover el aprendizaje entre estudiantes de Ingeniería de Sistemas en Manizales, Colombia," ', False), ("Edutec, Rev. Electrón. Tecnol. Educ.", True), (", no. 91, pp. 140-156, 2025.", False)],
    29: [('B. S. Manosalva Pedraza y M. Y. Criollo Guerrero, "Plataformas colaborativas como estrategia intersectorial en la educación pública," monografía, Universidad Nacional Abierta y a Distancia, Colombia, 2026. [En línea]. Disponible: https://repository.unad.edu.co/handle/10596/84233', False)],
    30: [('V. Wiesner y C. Burgoa, "Experiencia de un aprendizaje colaborativo internacional entre instituciones de Educación Superior de Colombia y Chile," ', False), ("Praxis & Saber", True), (", vol. 14, no. 37, 2023.", False)],
    31: [('Ministerio de Educación Nacional de Colombia, "Colombia Aprende." [En línea]. Disponible: https://www.colombiaaprende.edu.co/. [Accedido: 4-oct-2026].', False)],
    32: [('Ministerio de Tecnologías de la Información y las Comunicaciones de Colombia, "Gobierno Digital." [En línea]. Disponible: https://gobiernodigital.mintic.gov.co/. [Accedido: 4-oct-2026].', False)],
    33: [('Universidad Nacional de Colombia, "Portal institucional." [En línea]. Disponible: https://unal.edu.co/. [Accedido: 4-oct-2026].', False)],
    34: [('Servicio Nacional de Aprendizaje (SENA), "SOFIA Plus." [En línea]. Disponible: https://sofiaplus.sena.edu.co/. [Accedido: 4-oct-2026].', False)],
    35: [('Superintendencia de Industria y Comercio, "Protección de datos personales." [En línea]. Disponible: https://www.sic.gov.co/. [Accedido: 4-oct-2026].', False)],
    36: [('Universidad del Tolima, "Campus Virtual." [En línea]. Disponible: https://campusvirtual.ut.edu.co/. [Accedido: 4-oct-2026].', False)],
    37: [('Universidad del Tolima, IDEAD, "Tu Aula Virtual." [En línea]. Disponible: https://tuaulavirtual.ut.edu.co/. [Accedido: 4-oct-2026].', False)],
    38: [('Institución Universitaria de El Espinal (UNIESPINAL), "Aula Virtual UNIESPINAL." [En línea]. Disponible: https://aulavirtual.itfip.edu.co/. [Accedido: 4-oct-2026].', False)],
    39: [('Universidad de Ibagué, "Sitio institucional." [En línea]. Disponible: https://www.unibague.edu.co/. [Accedido: 4-oct-2026].', False)],
    40: [('Instituto Tolimense de Formación Técnica Profesional (ITFIP), "Programación de encuentro de semilleros 2020," Espinal, Colombia, 27 abr. 2020. [En línea]. Disponible: https://itfip.edu.co/programacion-de-encuentro-de-semilleros-2020/', False)],
    41: [("F. G. Ortiz Uribe y M. del P. García Nieto, ", False), ("Metodología de la investigación: el proceso y sus técnicas", True), (". México: Limusa, 2006.", False)],
    42: [("República de Colombia, Constitución Política de Colombia, art. 15, 1991.", False)],
    43: [("Presidencia de la República de Colombia, Decreto 1377 de 2013, Por el cual se reglamenta parcialmente la Ley 1581 de 2012, 27 jun. 2013, Diario Oficial No. 48.834.", False)],
    44: [("Congreso de la República de Colombia, Ley 1273 de 2009, Por medio de la cual se modifica el Código Penal y se crea un nuevo bien jurídico tutelado denominado de la protección de la información y de los datos, 5 ene. 2009, Diario Oficial No. 47.223.", False)],
    45: [("Congreso de la República de Colombia, Ley 23 de 1982, Sobre derechos de autor, 28 ene. 1982.", False)],
    46: [("Comisión del Acuerdo de Cartagena, Decisión 351 de 1993, Régimen común sobre derecho de autor y derechos conexos, 17 dic. 1993.", False)],
    47: [("Congreso de la República de Colombia, Ley 30 de 1992, Por la cual se organiza el servicio público de la educación superior, 28 dic. 1992, Diario Oficial No. 40.700.", False)],
}


INTL = [
    "Hodges et al. distinguieron la enseñanza remota de emergencia de un diseño en línea planificado. UNIESPINAL no necesita otro chat de crisis, sino un canal pensado para quedarse [3].",
    "Dhawan sintetizó ventajas y barreras del aprendizaje en línea y advirtió que la tecnología sola no garantiza equidad ni orden [4].",
    "Rapanta et al. mostraron que el profesorado universitario tuvo que reaprender a diseñar la mediación, no solo a encender una videollamada [11].",
    "Bond et al. mapearon la investigación de esa emergencia y confirmaron la saturación de herramientas y la fatiga digital [12].",
    "Adedoyin y Soykan subrayaron la brecha de conectividad y de competencias. Konex atiende ese límite al ser liviano y usable en el navegador del celular [13].",
    "Turnbull, Chugh y Luck revisaron décadas de investigación sobre LMS y mostraron que Moodle, Canvas o Blackboard organizan el curso, pero no siempre la vida informal del estudiante [6].",
    "Al-Maroof et al. estudiaron la adopción de Google Meet en pandemia y hallaron que la facilidad percibida impulsa el uso. Konex toma la lección de no cargar la interfaz [7].",
    "Smutny y Schreiberova revisaron chatbots educativos en Facebook Messenger y advirtieron la dependencia de una red comercial [14].",
    "Pérez, Daradoumis y Marquès redescubrieron el chatbot en educación y pidieron evaluaciones más rigurosas [8].",
    "Okonkwo y Ade-Ibijola sistematizaron aplicaciones de chatbots en apoyo administrativo, tutoría y evaluación [9].",
    "Labadze, Grigolia y Machaidze revisaron sesenta y siete estudios: el estudiante usa el chatbot para tareas y personalización; el docente gana tiempo; hay riesgos de exactitud y ética [15].",
    "Crompton y Burke describieron el estado de la inteligencia artificial en educación superior: personalización, soporte y automatización, con vacíos de privacidad [16].",
    "Kasneci et al. discutieron oportunidades y riesgos de los modelos de lenguaje en el aula y pidieron supervisión humana [17].",
    "La UNESCO publicó una guía para la IA generativa en educación que insiste en la centralidad de la persona, la transparencia y la protección de datos [18].",
    "La OCDE, en su panorama de educación digital, documentó que la gobernanza de los datos es tan crítica como la plataforma [19].",
    "Fiock actualizó la Comunidad de Indagación: un entorno digital necesita presencia social, cognitiva y docente; un repositorio sin diálogo no colabora [20].",
    "Martin, Sun y Westine revisaron dos décadas de investigación en línea y hallaron menos énfasis en la comunidad extra-aula, justo el hueco de Konex [21].",
    "Zhang, Zou y Cheng revisaron el aprendizaje asistido por chatbot y pidieron más evidencia de colaboración real, no solo de respuestas automáticas [22].",
    "Chiu et al. analizaron la IA en educación y advirtieron el riesgo de copiar sin comprender [23].",
    "Tlili et al. estudiaron ChatGPT como caso y concluyeron que las instituciones necesitan políticas propias, no improvisación [24].",
]

DEPT = [
    "La Universidad del Tolima opera el Campus Virtual para pregrados y posgrados a distancia. Es el referente departamental de mayor cobertura, pensado para programas virtuales y no para la colaboración cotidiana por asignatura y trámite [36].",
    "El IDEAD de la Universidad del Tolima mantiene Tu Aula Virtual, con recuperación de clave y soporte propio. Confirma cultura de aula en línea en Ibagué, anclada al curso y no a la comunidad [37].",
    "UNIESPINAL, antes ITFIP, dispone de Aula Virtual sobre Moodle para cursos y cátedra institucional. Digitaliza la formación y, a la vez, deja visible el hueco: no hay grupos de estudio horizontales ni gestión de horas sociales [38].",
    "La Universidad de Ibagué articula servicios digitales de ventanilla y comunicación institucional desde la sede Ambalá. Es un antecedente de ciudad sobre canales oficiales, aunque no publica un producto equivalente a Konex [39].",
    "En 2020, el encuentro de semilleros del entonces ITFIP se hizo por Google Meet, sala por programa. Esa improvisación de pandemia es la práctica que este proyecto quiere superar con un canal propio y ordenado [40].",
]

NAC = [
    "Espinel, Hernández y Prada compararon WhatsApp y el campus virtual con 606 estudiantes de Comunicación Social. WhatsApp construye comunidad, pero no es un ambiente virtual de aprendizaje [1].",
    "Echeverri y Manjarrés implementaron un asistente académico con procesamiento de lenguaje natural en el Politécnico Colombiano Jaime Isaza Cadavid y mejoraron tiempos de atención en trámites [2].",
    "García-Chitiva revisó el aprendizaje colaborativo mediado por internet en educación superior y lo situó como construcción social, no como suma de archivos [5].",
    "Veytia y Bastidas analizaron WhatsApp como recurso de trabajo grupal universitario y confirmaron su utilidad táctica y su desorden [25].",
    "Guiñez-Cabrera y Mansilla estudiaron WhatsApp Web con fines académicos en pandemia y hallaron inmediatez junto con sobrecarga [26].",
    "Villamizar y Viviescas, en la Universidad Autónoma de Bucaramanga, desarrollaron una plataforma web colaborativa con foros, recursos y un asistente de IA. El contexto no es el Tolima ni incluye horas sociales ni roles de directivos [27].",
    "Mejia-Lobo, Gil-Fernández y Calderón-Garrido evaluaron Ingeniemos.net en cuatro universidades de Manizales, con 280 estudiantes de Ingeniería de Sistemas. La interacción incrementó el aprendizaje, pero el objeto era una carrera, no toda la comunidad [28].",
    "Manosalva y Criollo revisaron experiencias colombianas de plataformas colaborativas en educación pública y propusieron un Ecosistema Colaborativo Educativo; su escala es de política, no de un MVP institucional [29].",
    "Wiesner y Burgoa documentaron aprendizaje colaborativo internacional entre instituciones de Colombia y Chile, útil para pensar la colaboración más allá del aula local [30].",
    "El Ministerio de Educación Nacional sostiene portales como Colombia Aprende, que centralizan recursos públicos, pero no resuelven la vida cotidiana de un campus pequeño [31].",
    "MinTIC ha impulsado gobierno digital y servicios en línea. Ese marco legitima digitalizar trámites; no sustituye el diseño pedagógico de Konex [32].",
    "La Universidad Nacional de Colombia opera campus y servicios virtuales de gran escala. Su lección es la necesidad de roles y de soporte, no copiar esa complejidad [33].",
    "El SENA, con SOFIA Plus, demuestra que un país puede concentrar matrícula y trámites de formación en un solo sistema, con otra gobernanza y otro usuario [34].",
    "La Superintendencia de Industria y Comercio ha orientado el cumplimiento del habeas data en aplicaciones digitales, refuerzo práctico de la Ley 1581 [35].",
    "Esos referentes nacionales muestran que WhatsApp no es campus, que un chatbot institucional ahorra ventanilla y que una red académica propia incrementa el aprendizaje. Falta, en una institución de ciudad intermedia, un desarrollo liviano de roles mixtos que trate el dato personal con el rigor de la Ley 1581. Ese es el lugar de Konex [1], [2], [10].",
]


def build():
    assert len(MAP) == 47
    assert len(RAW_REFS) == 47
    assert len(INTL) == 20 and len(DEPT) == 5 and len(NAC) == 15

    doc = Document()
    sec0 = doc.sections[0]
    sec0.page_width = Inches(8.5)
    sec0.page_height = Inches(11)
    sec0.top_margin = Inches(0.75)
    sec0.bottom_margin = Inches(0.75)
    sec0.left_margin = Inches(0.75)
    sec0.right_margin = Inches(0.75)
    page_number_footer(sec0)

    para(
        doc,
        "Desarrollo de una plataforma web colaborativa para estudiantes, docentes y directivos en UNIESPINAL",
        22, True, False, "center", 6, 10, 0,
    )
    para(
        doc,
        "Eddy Santiago Vargas Tique, Nicolás Esteban Martínez González y Damián Jesús Barbosa Álvarez",
        11, False, False, "center", 0, 8, 0,
    )
    para(doc, "Institución Universitaria de El Espinal (UNIESPINAL)", 10, False, False, "center", 0, 0, 0)
    para(doc, "Facultad de Ingeniería y Ciencias Agroindustriales", 10, False, False, "center", 0, 0, 0)
    para(doc, "Técnico profesional en programación web — Ingeniería de sistemas", 10, False, False, "center", 0, 0, 0)
    para(doc, "Espinal, Tolima, Colombia", 10, False, False, "center", 0, 4, 0)
    para(doc, "Asesores: Álvaro José Feria Rodríguez; Jeniffer Julieth Palencia Quezada", 9, False, False, "center", 0, 0, 0)
    para(doc, "Profesor: Juan David Diaz Valencia", 9, False, False, "center", 0, 0, 0)
    para(doc, "Octubre de 2026", 9, False, False, "center", 0, 12, 0)

    para(doc, "Abstract", 10, True, True, "left", 4, 4, 0)
    para(
        doc,
        "Este trabajo justifica y fundamenta Konex, una plataforma web colaborativa para estudiantes, docentes y directivos de UNIESPINAL. El problema es la fragmentación de la vida académica e institucional en chats informales, donde se pierden apuntes, grupos, eventos y trámites como las horas sociales. El objetivo general es desarrollar un entorno web seguro que centralice esos procesos. Se revisan cuarenta antecedentes de 2020 a 2026 (veinte internacionales, cinco departamentales y quince nacionales) y se articulan los marcos administrativo, teórico, legal y conceptual con la Ley 1581 de 2012. El alcance es el desarrollo y la validación del prototipo, no la implementación institucional en producción.",
        9, False, False, "justify", 0, 6, 0.17,
    )
    para(
        doc,
        "Palabras clave: plataforma colaborativa, educación superior, Laravel, Ley 1581 de 2012, UNIESPINAL, aprendizaje colaborativo.",
        9, False, True, "justify", 0, 10, 0,
    )

    body = doc.add_section(WD_SECTION.CONTINUOUS)
    body.page_width = Inches(8.5)
    body.page_height = Inches(11)
    body.top_margin = Inches(0.75)
    body.bottom_margin = Inches(0.75)
    body.left_margin = Inches(0.75)
    body.right_margin = Inches(0.75)
    set_two_columns(body)
    body.header.is_linked_to_previous = True
    body.footer.is_linked_to_previous = True

    h_roman(doc, "I. JUSTIFICACIÓN")
    para(doc, C("La justificación de Konex es social, práctica y teórica. En lo social, la colaboración desigual afecta el rendimiento y la permanencia. Quien no está en el chat correcto se entera tarde de una convocatoria, de un grupo o de un trámite de horas sociales. Una plataforma institucional reduce esa asimetría porque convierte la información en un hecho consultable, no en un rumor de grupo [1], [5]. En lo práctico, el proyecto entrega un software usable desde el navegador, sin exigir una aplicación nativa, y concentra en un solo entorno lo que hoy está repartido en varias aplicaciones."))
    para(doc, "Konex centraliza, en un entorno seguro, la comunicación institucional, el intercambio de recursos académicos, la organización de grupos de estudio, la publicación de eventos y noticias, y la gestión de solicitudes de horas sociales en UNIESPINAL. No está pensada únicamente para el estudiante: reúne a estudiantes, profesores y directivos. Se plantea como producto mínimo viable de dieciséis semanas. El alcance es el desarrollo y la validación del prototipo, no la implementación en producción.")
    para(doc, C("En lo técnico, Laravel permite autenticación, sesiones, roles y protecciones frente a CSRF e inyección SQL. MySQL organiza apuntes, publicaciones, eventos y solicitudes en catálogos por asignatura y por rol. Bootstrap y JavaScript dan una interfaz responsiva, adecuada al celular con el que la comunidad ya estudia. A diferencia de las redes comerciales, Konex no se diseña para retener al usuario con algoritmos de entretenimiento, sino para la productividad académica [6], [7]. Un agente de consulta institucional, alimentado por datos ya centralizados, evita buscar entre mensajes o esperar a que alguien responda [8], [9]."))
    para(doc, C("En lo teórico, el trabajo se sostiene en el aprendizaje colaborativo, en los entornos virtuales de aprendizaje y en la evidencia de que los chatbots institucionales acortan tiempos de atención cuando se alimentan de datos oficiales [5], [8], [9]. El aula virtual de cursos no reemplaza un espacio de comunidad. WhatsApp construye grupo, pero no es un ambiente virtual de aprendizaje [1]. El vacío que Konex ataca es local: UNIESPINAL tiene aula para cursos, pero no un espacio colaborativo que una a estudiantes, docentes y directivos alrededor de recursos, grupos y trámites."))
    para(doc, C("También hay una justificación normativa. Al autenticar usuarios con correo institucional y al guardar publicaciones, solicitudes y consultas, el sistema trata datos personales. La Ley 1581 de 2012 obliga a recoger solo lo necesario, a informar la finalidad y a proteger la información [10]. Ese deber orienta el diseño de la autenticación, de la recuperación de acceso y del agente de consulta, que no debe guardar historiales invasivos ni rastros ajenos a su función."))

    h_roman(doc, "II. OBJETIVOS")
    h_letter(doc, "A. Objetivo general")
    para(doc, "Desarrollar una plataforma web colaborativa para el intercambio de recursos académicos, la gestión de grupos de estudio y la circulación de información institucional entre estudiantes, docentes y directivos de UNIESPINAL.")
    h_letter(doc, "B. Objetivos específicos")
    para(doc, "1) Analizar la arquitectura del software mediante Laravel, MySQL, Bootstrap y JavaScript, de modo que la interfaz sea dinámica, intuitiva y adaptable a dispositivos móviles.")
    para(doc, "2) Diseñar un espacio centralizado que permita crear publicaciones, clasificar y compartir apuntes digitales por asignatura, y organizar grupos de estudio de forma ágil.")
    para(doc, "3) Desarrollar un módulo de comunicación e interacción que facilite la difusión de eventos académicos, talleres y oportunidades formativas en un entorno seguro y enfocado en la productividad.")
    para(doc, C("4) Implementar autenticación por roles, recuperación de acceso y un agente de consulta institucional, con tratamiento de datos conforme a la Ley 1581 de 2012 [10]."))

    h_roman(doc, "III. ANTECEDENTES")
    para(doc, "Se presentan cuarenta antecedentes situados en los últimos seis años (2020-2026): veinte internacionales, cinco departamentales y quince nacionales. El criterio fue la cercanía con plataformas colaborativas, entornos virtuales, mensajería informal, chatbots institucionales o gestión digital de la educación superior.")

    h_letter(doc, "A. Antecedentes internacionales")
    for t in INTL:
        para(doc, C(t))

    h_letter(doc, "B. Antecedentes departamentales")
    for t in DEPT:
        para(doc, C(t))

    h_letter(doc, "C. Antecedentes nacionales")
    for t in NAC:
        para(doc, C(t))

    h_roman(doc, "IV. MARCOS DE REFERENCIA")
    h_letter(doc, "A. Marco administrativo")
    para(
        doc,
        "El proyecto se adelanta en la Institución Universitaria de El Espinal (UNIESPINAL), Facultad de Ingeniería y Ciencias Agroindustriales, programa de Técnico profesional en programación web, con acompañamiento de Ingeniería de Sistemas. Los investigadores son:",
    )
    para(doc, "Eddy Santiago Vargas Tique — Investigador.", first=0.17)
    para(doc, "Nicolás Esteban Martínez González — Investigador principal y desarrollador.", first=0.17)
    para(doc, "Damián Jesús Barbosa Álvarez — Desarrollador e investigador.", first=0.17)
    para(doc, "Álvaro José Feria Rodríguez — Asesor.", first=0.17)
    para(doc, "Jeniffer Julieth Palencia Quezada — Asesora.", first=0.17)
    para(doc, "Konex se concibe como prototipo de aula y de laboratorio, no como sistema misional de la Rectoría. Los roles del software —estudiante, docente y directivo— copian la estructura real de la comunidad. El cronograma de dieciséis semanas organiza análisis, diseño, construcción, pruebas e informe. Los recursos son de los investigadores, con software libre y sin carga presupuestal institucional. El producto podrá registrarse como soporte lógico ante la Dirección Nacional de Derecho de Autor.")

    h_letter(doc, "B. Marco teórico")
    para(doc, C("El aprendizaje colaborativo sostiene que el conocimiento se construye en la interacción, no en el aislamiento [5], [20]. El CSCL y los entornos virtuales explican por qué un medio digital puede mediar la construcción conjunta si hay herramientas de comunicación, de compartición y de coordinación [21], [22]. La Comunidad de Indagación añade que sin presencia social y docente el entorno se vuelve repositorio muerto [20]. Por eso Konex incluye publicación, comentario y roles que emiten información oficial."))
    para(doc, C("Los estudios de LMS muestran que la utilidad percibida y la facilidad de uso predicen la adopción [6], [7]. El patrón Modelo-Vista-Controlador, materializado en Laravel, separa datos, interfaz y control. La investigación aplicada-tecnológica, según Ortiz Uribe y García Nieto, justifica probar la hipótesis en el prototipo: el criterio es la efectividad de lo construido [41]. La literatura de chatbots institucionales sostiene que un agente es útil cuando responde con datos de la organización [2], [9], [15]."))

    h_letter(doc, "C. Marco legal")
    para(doc, C("La Constitución Política de Colombia, en su artículo 15, garantiza la intimidad, el buen nombre y el habeas data [42]. Ese derecho se desarrolla en la Ley 1581 de 2012, régimen general de protección de datos personales, norma axial de este proyecto [10]."))
    para(doc, "La Ley 1581 de 2012 aplica porque Konex autentica usuarios con correo institucional y almacena nombre, rol, publicaciones, solicitudes de horas sociales y, si se habilita, consultas al agente. En la etapa de prototipo se tratan datos de prueba o de participantes que hayan consentido. Los principios que se implementan son legalidad, finalidad, libertad, veracidad, transparencia, acceso restringido, seguridad y confidencialidad. El principio de minimización se traduce en no pedir datos ajenos al funcionamiento, no guardar historial invasivo y no emplear algoritmos de retención comercial. La recuperación de acceso se plantea para que el usuario no pierda la cuenta y, a la vez, para no exponer secretos en texto plano.")
    para(doc, C("El Decreto 1377 de 2013 reglamenta la Ley 1581 y precisa consentimiento, políticas de tratamiento y medidas de seguridad [43]. La Ley 1273 de 2009 tipifica los atentados contra la confidencialidad, integridad y disponibilidad de los datos, y obliga a cifrar contraseñas, validar formularios y restringir el acceso por rol [44]. La Ley 23 de 1982 y la Decisión 351 de la Comunidad Andina protegen el software como obra y recuerdan que los apuntes ajenos no se republican sin respeto al derecho de autor [45], [46]. La Ley 30 de 1992 organiza la educación superior y sitúa a la institución como responsable del servicio educativo; Konex no reemplaza el aula ni el reglamento estudiantil [47]."))

    h_letter(doc, "D. Marco conceptual")
    h_concept(doc, "Aprendizaje colaborativo.", "Proceso en el que el saber se construye con pares, profesores y demás miembros de la institución.")
    h_concept(doc, "Repositorio académico centralizado.", "Almacén organizado de apuntes, guías, talleres, eventos, noticias y solicitudes, distinto del chat porque permite buscar y reutilizar.")
    h_concept(doc, "Arquitectura MVC.", "Separación de modelo, vista y controlador. En Laravel evita mezclar la base de datos con la interfaz y con las reglas de cada rol.")
    h_concept(doc, "Producto mínimo viable.", "Autenticación por roles, grupos, repositorio, eventos, horas sociales, recuperación de acceso y agente de consulta. Quedan fuera la app nativa y la puesta en producción.")
    h_concept(doc, "Control de acceso basado en roles.", "El estudiante colabora y solicita; el docente y el directivo pueden emitir información oficial.")
    h_concept(doc, "Agente de consulta institucional.", "Interpreta una pregunta y responde con datos ya centralizados. No navega internet ni entretiene.")
    h_concept(doc, "Asimetría de la información.", "Unos saben y otros no. Konex la reduce al publicar en un solo lugar.")
    h_concept(doc, "Dato personal y tratamiento.", C("Según la Ley 1581, dato personal es cualquier información vinculada a una persona determinada o determinable, y tratamiento es cualquier operación sobre ese dato [10]. En Konex, el correo institucional y el perfil son datos personales."))

    h_roman(doc, "V. REFERENCIAS")
    inverse = {new: old for old, new in MAP.items()}
    for new_n in range(1, 48):
        old_n = inverse[new_n]
        ref_item(doc, new_n, RAW_REFS[old_n])

    doc.save(OUT)

    # audit
    text = "\n".join(p.text for p in doc.paragraphs)
    cited = set()
    import re
    for m in re.finditer(r"\[(\d+)\]", text):
        n = int(m.group(1))
        if n <= 47:
            cited.add(n)
    missing_cite = [i for i in range(1, 48) if i not in cited]
    extra = sorted({int(m.group(1)) for m in re.finditer(r"\[(\d+)\]", text) if int(m.group(1)) > 47})

    report = f"""Informe de revisión — Konex-Tarea-IEEE-FINAL.docx

1. Referencias en la lista final: 47 ([1] a [47]).
2. Referencias citadas al menos una vez en el texto: {len(cited)}.
3. Referencias no citadas: {missing_cite if missing_cite else "ninguna"}.
4. Números de cita fuera de 1-47: {extra if extra else "ninguno"}.
5. Antecedentes: 40 (20 internacionales, 5 departamentales, 15 nacionales).
6. Secciones principales: I. Justificación; II. Objetivos; III. Antecedentes; IV. Marcos de referencia; V. Referencias.
7. Autores: Eddy Santiago Vargas Tique; Nicolás Esteban Martínez González; Damián Jesús Barbosa Álvarez.
8. Asesores conservados: Álvaro José Feria Rodríguez; Jeniffer Julieth Palencia Quezada.
9. Profesor conservado del documento fuente: Juan David Diaz Valencia.
10. Leyes conservadas: Constitución art. 15; Ley 1581 de 2012; Decreto 1377 de 2013; Ley 1273 de 2009; Ley 23 de 1982; Decisión 351 de 1993; Ley 30 de 1992.
11. Cambios de formato:
    - Primera página estilo artículo IEEE (título, autores, filiación), sin portada APA.
    - Cuerpo a dos columnas, Times New Roman, márgenes de 0,75 pulgadas, carta (Letter).
    - Títulos en números romanos (I–V) y subtítulos A, B, C, D.
    - Antecedentes en párrafos académicos, sin 3.1.1 / 3.1.2.
    - Citas IEEE [n] reordenadas según la primera aparición en el texto.
    - Las 47 fichas bibliográficas se conservaron; solo cambió su número cuando el orden IEEE lo exigía.
    - Se añadió un Abstract redactado solo con información ya presente (no se inventaron resultados ni metodología nueva). El resumen no lleva citas numeradas, como es habitual en artículos IEEE; las 47 fuentes se citan en el cuerpo.
12. Problemas no corregibles por falta de información:
    - Varias referencias institucionales (MEN, MinTIC, SENA, SIC, UT, UNIESPINAL, Unibagué, UNAL) no traen DOI; se conservó URL y fecha de acceso.
    - Algunas revistas no incluían DOI en el documento original; no se inventó.
    - Turnbull et al. [ahora {MAP[6]}] no traía volumen/páginas en la fuente original.
    - El documento fuente no indicaba grupo ni código de proyecto; no se añadieron.
"""
    with open(REPORT, "w", encoding="utf-8") as f:
        f.write(report)
    print(OUT)
    print("cited", sorted(cited), "count", len(cited))
    print("missing", missing_cite)


if __name__ == "__main__":
    build()
