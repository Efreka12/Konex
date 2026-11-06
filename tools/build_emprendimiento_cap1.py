# -*- coding: utf-8 -*-
"""Sección preliminar y Capítulo 1 del proyecto de emprendimiento Konex, normas IEEE 2025."""

from docx import Document
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING, WD_TAB_ALIGNMENT, WD_TAB_LEADER
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Cm, Inches, Mm, Pt, RGBColor

OUT = r"C:\Users\nico1\Downloads\Konex_Seccion_y_Capitulo1_IEEE.docx"
LOGO = r"C:\Users\nico1\Desktop\DESARROLLO DE  UNA PLATAFORMA WEB COLABORATIVA PARA ESTUDIANTES, DOCENTES Y DIRECTIVOS EN UNIESPINAL\konex\public\img\konex-mark.png"


def font(run, size=12, bold=False, italic=False):
    run.font.name = "Times New Roman"
    run._element.rPr.rFonts.set(qn("w:eastAsia"), "Times New Roman")
    run._element.rPr.rFonts.set(qn("w:ascii"), "Times New Roman")
    run._element.rPr.rFonts.set(qn("w:hAnsi"), "Times New Roman")
    run.font.size = Pt(size)
    run.bold = bold
    run.italic = italic
    run.font.color.rgb = RGBColor(0, 0, 0)


def space(p, before=0, after=8, first=0):
    p.paragraph_format.space_before = Pt(before)
    p.paragraph_format.space_after = Pt(after)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.SINGLE
    p.paragraph_format.line_spacing = 1.0
    p.paragraph_format.first_line_indent = Cm(first)


def set_page_start(section, start=1):
    sect_pr = section._sectPr
    pg_num = sect_pr.find(qn("w:pgNumType"))
    if pg_num is None:
        pg_num = OxmlElement("w:pgNumType")
        sect_pr.append(pg_num)
    pg_num.set(qn("w:start"), str(start))


def add_page_number(section):
    header = section.header
    header.is_linked_to_previous = False
    p = header.paragraphs[0]
    p.alignment = WD_ALIGN_PARAGRAPH.RIGHT
    run = p.add_run()
    font(run, 10)
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


def hide_header(section):
    section.header.is_linked_to_previous = False
    if section.header.paragraphs:
        section.header.paragraphs[0].clear()


def setup_page(section):
    section.page_width = Mm(210)
    section.page_height = Mm(297)
    section.top_margin = Inches(1)
    section.bottom_margin = Inches(1)
    section.left_margin = Inches(1)
    section.right_margin = Inches(1)
    section.header_distance = Cm(1.25)
    section.footer_distance = Cm(1.25)


def cover_line(doc, text, size=12, bold=False, before=0, after=4, italic=False):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, before, after)
    if text:
        run = p.add_run(text)
        font(run, size, bold=bold, italic=italic)
    return p


def body(doc, text, first=1.27):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(p, 0, 8, first)
    run = p.add_run(text)
    font(run)
    return p


def center(doc, text, size=12, bold=False, italic=False, before=0, after=8):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, before, after)
    run = p.add_run(text)
    font(run, size, bold=bold, italic=italic)
    return p


def h1(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 12, 12)
    run = p.add_run(text)
    font(run, 12, bold=True)
    return p


def h2(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 12, 0)
    run = p.add_run(text)
    font(run, 12, italic=True)
    return p


def h3(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 12, 0)
    run = p.add_run(text)
    font(run, 12, italic=True)
    return p


def page_break(doc):
    doc.add_page_break()


def toc_line(doc, title, page="", level=0):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 2, 2, 0)
    indent = 0.5 * level
    p.paragraph_format.left_indent = Inches(indent)
    p.paragraph_format.tab_stops.add_tab_stop(
        Inches(6.5), WD_TAB_ALIGNMENT.RIGHT, WD_TAB_LEADER.DOTS
    )
    run = p.add_run(title)
    font(run, 12, bold=(level == 0 and title.startswith("CAPÍTULO")))
    if page:
        run2 = p.add_run("\t" + str(page))
        font(run2, 12)
    return p


def shade_cell(cell, hex_color):
    tc = cell._tc
    tc_pr = tc.get_or_add_tcPr()
    shd = OxmlElement("w:shd")
    shd.set(qn("w:fill"), hex_color)
    shd.set(qn("w:val"), "clear")
    tc_pr.append(shd)


def set_cell_border(cell):
    tc = cell._tc
    tc_pr = tc.get_or_add_tcPr()
    tc_borders = OxmlElement("w:tcBorders")
    for edge in ("top", "left", "bottom", "right"):
        element = OxmlElement(f"w:{edge}")
        element.set(qn("w:val"), "single")
        element.set(qn("w:sz"), "4")
        element.set(qn("w:space"), "0")
        element.set(qn("w:color"), "000000")
        tc_borders.append(element)
    tc_pr.append(tc_borders)


def cell_text(cell, text, size=10, bold=False, align="left"):
    cell.text = ""
    p = cell.paragraphs[0]
    p.alignment = {
        "left": WD_ALIGN_PARAGRAPH.LEFT,
        "center": WD_ALIGN_PARAGRAPH.CENTER,
        "right": WD_ALIGN_PARAGRAPH.RIGHT,
    }[align]
    space(p, 2, 2)
    run = p.add_run(text)
    font(run, size, bold=bold)
    set_cell_border(cell)


def add_table(doc, headers, rows, caption):
    cap = doc.add_paragraph()
    cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(cap, 10, 6)
    run = cap.add_run(caption)
    font(run, 12, bold=True)

    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = True
    for i, header in enumerate(headers):
        cell_text(table.rows[0].cells[i], header, 10, True, "center")
        shade_cell(table.rows[0].cells[i], "D9D9D9")
    for r, row in enumerate(rows):
        for c, value in enumerate(row):
            align = "center" if c == 0 else "left"
            cell_text(table.rows[r + 1].cells[c], value, 10, False, align)

    note = doc.add_paragraph()
    note.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(note, 2, 10)
    run = note.add_run("Fuente: elaboración propia.")
    font(run, 8, italic=True)
    return table


def ref_line(doc, number, parts):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.left_indent = Cm(1.27)
    p.paragraph_format.first_line_indent = Cm(-1.27)
    space(p, 0, 8)
    first = True
    for text, italic in parts:
        prefix = f"[{number}] " if first else ""
        run = p.add_run(prefix + text)
        font(run, 10, italic=italic)
        first = False


def build():
    doc = Document()
    prelim = doc.sections[0]
    setup_page(prelim)
    hide_header(prelim)
    prelim.footer.is_linked_to_previous = False

    # ------------------------------------------------------------------
    # 1. Portada
    # ------------------------------------------------------------------
    cover_line(doc, "INSTITUCIÓN UNIVERSITARIA DE EL ESPINAL", 14, True, 0, 2)
    cover_line(doc, "UNIESPINAL", 16, True, 0, 2)
    cover_line(doc, "Facultad de Ingeniería y Ciencias Agroindustriales", 12, False, 4, 2)
    cover_line(doc, "Técnico Profesional en Programación Web", 12, False, 0, 18)

    try:
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        space(p, 6, 8)
        run = p.add_run()
        run.add_picture(LOGO, width=Inches(1.35))
    except Exception:
        pass

    cover_line(doc, "KONEX", 20, True, 4, 6)
    cover_line(
        doc,
        "Desarrollo de una Plataforma Web Colaborativa para Estudiantes, Docentes y Directivos en UNIESPINAL",
        14,
        True,
        6,
        10,
    )
    cover_line(doc, "Proyecto de emprendimiento", 12, italic=True, before=4, after=18)

    cover_line(doc, "Integrantes", 12, True, 8, 4)
    cover_line(doc, "Nicolás Esteban Martínez González", 12, False, 0, 2)
    cover_line(doc, "Damián Jesús Barbosa Álvarez", 12, False, 0, 10)

    cover_line(doc, "Asesores", 12, True, 4, 4)
    cover_line(doc, "Álvaro José Feria Rodríguez", 12, False, 0, 2)
    cover_line(doc, "Jeniffer Julieth Palencia Quezada", 12, False, 0, 16)

    cover_line(doc, "El Espinal, Tolima", 12, False, 12, 2)
    cover_line(doc, "Noviembre de 2026", 12, False, 0, 2)

    page_break(doc)

    # ------------------------------------------------------------------
    # 2. Contraportada
    # ------------------------------------------------------------------
    cover_line(doc, "Datos de presentación del trabajo", 14, True, 12, 16)
    cover_line(
        doc,
        "Desarrollo de una Plataforma Web Colaborativa para Estudiantes, Docentes y Directivos en UNIESPINAL",
        13,
        True,
        0,
        8,
    )
    cover_line(doc, "Nombre comercial del emprendimiento: Konex", 12, False, 4, 4)
    cover_line(doc, "Modalidad: proyecto de emprendimiento digital", 12, False, 0, 4)
    cover_line(doc, "Asignatura: Proyecto de emprendimiento", 12, False, 0, 4)
    cover_line(doc, "Programa: Técnico Profesional en Programación Web", 12, False, 0, 4)
    cover_line(doc, "Facultad: Ingeniería y Ciencias Agroindustriales", 12, False, 0, 4)
    cover_line(doc, "Institución: UNIESPINAL", 12, False, 0, 16)

    cover_line(doc, "Equipo de trabajo", 12, True, 8, 8)
    add_table(
        doc,
        ["Nombre", "Documento", "Correo", "Rol"],
        [
            [
                "Nicolás Esteban Martínez González",
                "1192750306",
                "ngonzales06@itfip.edu.co",
                "Investigador principal y desarrollador",
            ],
            [
                "Damián Jesús Barbosa Álvarez",
                "1028884579",
                "dbarbosa79@itfip.edu.co",
                "Desarrollador principal e investigador",
            ],
        ],
        "Tabla 1. Integrantes del emprendimiento Konex.",
    )

    body(
        doc,
        "El presente documento constituye el avance de la sección preliminar y del Capítulo 1 del proyecto de emprendimiento Konex. Se elaboró según la estructura solicitada por la asignatura y se presenta con las normas IEEE 2025 adoptadas por el Comité Curricular del programa de Ingeniería de Sistemas de UNIESPINAL. El alcance de este entregable cubre la portada, la contraportada, la tabla de contenido, la introducción, la justificación, los objetivos y la información general del emprendimiento. Los capítulos de producto, marketing digital, análisis económico y cierre se desarrollarán en las siguientes clases.",
        first=1.27,
    )
    body(
        doc,
        "Konex es una plataforma web colaborativa dirigida a la comunidad de UNIESPINAL. Su propósito es concentrar, en un solo entorno seguro, el intercambio de recursos académicos, la organización de grupos de estudio, la publicación de eventos y noticias, la gestión de horas sociales y la consulta de información institucional mediante un agente de inteligencia artificial. El trabajo se limita al desarrollo y a la validación del prototipo; no contempla, en esta etapa, la implementación oficial en la institución.",
    )

    page_break(doc)

    # ------------------------------------------------------------------
    # 3. Tabla de contenido
    # ------------------------------------------------------------------
    center(doc, "Tabla de contenido", 12, True, False, 0, 16)

    toc = [
        ("1. Portada", "i", 0),
        ("2. Contraportada", "ii", 0),
        ("3. Tabla de contenido", "iii", 0),
        ("4. Introducción", "1", 0),
        ("5. Justificación", "2", 0),
        ("6. Objetivos", "3", 0),
        ("6.1. Objetivo general", "3", 1),
        ("6.2. Objetivos específicos", "3", 1),
        ("CAPÍTULO 1. Información general del emprendimiento", "4", 0),
        ("7. Nombre del emprendimiento", "4", 0),
        ("8. Origen de la idea", "4", 0),
        ("9. Problema o necesidad", "5", 0),
        ("10. Estado actual", "6", 0),
        ("11. Identidad corporativa", "7", 0),
        ("11.1. Logotipo", "7", 1),
        ("11.2. Eslogan", "8", 1),
        ("11.3. Misión", "8", 1),
        ("11.4. Visión", "8", 1),
        ("11.5. Valores corporativos", "8", 1),
        ("CAPÍTULO 2. Producto o servicio", "—", 0),
        ("12. Descripción", "—", 0),
        ("13. Ficha técnica", "—", 0),
        ("14. Innovación", "—", 0),
        ("15. Valor agregado", "—", 0),
        ("CAPÍTULO 3. Estrategia de marketing digital", "—", 0),
        ("16. Público objetivo", "—", 0),
        ("17. Buyer persona", "—", 0),
        ("18. Canales digitales", "—", 0),
        ("19. Marketing de contenidos", "—", 0),
        ("20. Publicaciones", "—", 0),
        ("21. Calendario editorial", "—", 0),
        ("22. Comercialización digital", "—", 0),
        ("23. Pagos y compra", "—", 0),
        ("24. Indicadores", "—", 0),
        ("CAPÍTULO 4. Análisis económico", "—", 0),
        ("25. Costos", "—", 0),
        ("26. Costo unitario", "—", 0),
        ("27. Precio de venta", "—", 0),
        ("28. Punto de equilibrio", "—", 0),
        ("CIERRE. Elementos finales", "—", 0),
        ("29. Conclusiones", "—", 0),
        ("30. Referencias", "9", 0),
        ("31. Anexos", "—", 0),
    ]
    for title, page, level in toc:
        toc_line(doc, title, page, level)

    note = doc.add_paragraph()
    note.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(note, 16, 8, 0)
    run = note.add_run(
        "Nota. Los apartados 1 a 11 corresponden al avance de la presente entrega. Los capítulos 2, 3, 4 y el cierre se desarrollarán en las próximas clases. Las páginas marcadas con raya se actualizarán cuando esos apartados estén redactados."
    )
    font(run, 10, italic=True)

    # ------------------------------------------------------------------
    # Cuerpo
    # ------------------------------------------------------------------
    body_sec = doc.add_section()
    setup_page(body_sec)
    hide_header(prelim)
    body_sec.header.is_linked_to_previous = False
    add_page_number(body_sec)
    set_page_start(body_sec, 1)

    # ------------------------------------------------------------------
    # 4. Introducción
    # ------------------------------------------------------------------
    h1(doc, "4. Introducción")
    body(
        doc,
        "La experiencia universitaria no se agota en el aula. El estudiante, el docente y el directivo construyen la vida académica en la interacción cotidiana: armar un grupo de estudio, localizar un apunte fiable, enterarse de un evento, seguir una convocatoria o resolver un trámite de horas sociales. Ese proceso, que debería ser colaborativo y oportuno, se ha vuelto en muchas instituciones una trayectoria fragmentada. La información de valor circula en chats de mensajería instantánea, se pierde entre mensajes irrelevantes y llega tarde a quien más la necesita [1], [2].",
    )
    body(
        doc,
        "En UNIESPINAL esa fragmentación es visible. Los estudiantes inician cada semestre sin un canal único para identificar compañeros de asignatura, compartir material verificado o consultar un procedimiento institucional. Los docentes y los directivos tampoco disponen de un medio formal, persistente y auditable para publicar noticias, eventos o respuestas a solicitudes. WhatsApp y Telegram resuelven la urgencia, pero no sustituyen un entorno académico: construyen grupo y, al mismo tiempo, sepultan archivos, mezclan lo oficial con lo informal y dejan fuera a quien no está en el chat correcto [2], [3].",
    )
    body(
        doc,
        "Konex nace como respuesta a esa necesidad. Es un emprendimiento digital de los estudiantes Nicolás Esteban Martínez González y Damián Jesús Barbosa Álvarez, del programa de Técnico Profesional en Programación Web. La propuesta consiste en una plataforma web colaborativa que reúne, en un solo producto, autenticación por roles, repositorio de recursos, grupos de estudio, publicaciones, eventos, noticias, gestión de horas sociales y un agente de inteligencia artificial alimentado por información institucional ya centralizada. A diferencia de las redes comerciales, el producto no se diseña para retener al usuario con entretenimiento, sino para la productividad académica y la circulación oportuna de datos oficiales [4], [5].",
    )
    body(
        doc,
        "El presente documento corresponde al avance de la sección preliminar y del Capítulo 1 del proyecto de emprendimiento. La introducción sitúa el tema y el enfoque. La justificación explica la pertinencia social, práctica, técnica y normativa de la idea. Los objetivos declaran el propósito del producto. El Capítulo 1 presenta el nombre, el origen de la idea, el problema que atiende, el estado actual del emprendimiento y la identidad corporativa. Los capítulos posteriores, previstos por la asignatura, desarrollarán el producto, la estrategia de marketing digital, el análisis económico y el cierre.",
    )

    # ------------------------------------------------------------------
    # 5. Justificación
    # ------------------------------------------------------------------
    h1(doc, "5. Justificación")
    body(
        doc,
        "La justificación de Konex es social, práctica, técnica y normativa. En lo social, la colaboración desigual afecta el rendimiento y la permanencia. Quien no está en el grupo correcto se entera tarde de una entrega, de una convocatoria o del estado de un trámite. Esa asimetría de la información no es un rasgo menor de la vida universitaria: convierte un requisito institucional en una carrera de oídas. Una plataforma propia reduce esa desigualdad porque transforma apuntes, eventos, noticias y solicitudes en hechos consultables, no en rumores de chat [1], [2], [6].",
    )
    body(
        doc,
        "En lo práctico, el emprendimiento atiende una necesidad real y cotidiana de UNIESPINAL. No se propone otra red social ni un campus que copie Moodle. El aula virtual organiza el curso; Konex organiza la comunidad. El producto concentra, en un entorno accesible desde el navegador, lo que hoy está repartido en varias aplicaciones: material por asignatura, grupos de estudio, avisos oficiales, horas sociales y consulta inmediata. Esa concentración ahorra tiempo, disminuye la dependencia de un compañero “que sí sabe” y da a docentes y directivos un canal visible para emitir información oficial [2], [7], [8].",
    )
    body(
        doc,
        "En lo técnico, la viabilidad del software se sostiene en tres decisiones de ingeniería. Primera, Laravel aporta un marco Modelo-Vista-Controlador con autenticación, sesiones, roles y protecciones frente a CSRF e inyección SQL, lo que acorta el tiempo de desarrollo de un producto mínimo viable. Segunda, MySQL organiza usuarios, asignaturas, recursos, eventos, publicaciones y solicitudes en una estructura relacional que, además, alimenta al agente de consulta; las respuestas no salen de conversaciones informales, sino de datos centralizados. Tercera, Bootstrap y JavaScript permiten una interfaz responsiva, usable en el celular con el que la comunidad ya estudia, sin exigir una aplicación nativa en esta etapa [5], [9], [10].",
    )
    body(
        doc,
        "En lo teórico, el trabajo se apoya en el aprendizaje colaborativo mediado por internet, en la evidencia de que la mensajería instantánea no reemplaza un ambiente virtual de aprendizaje y en los estudios que muestran que un asistente institucional reduce tiempos de atención cuando se alimenta de información oficial [1], [2], [4], [5], [11]. En lo normativo, el sistema tratará credenciales, publicaciones y solicitudes. La Ley 1581 de 2012 obliga a recoger solo los datos necesarios y a protegerlos [12]. La Ley 1273 de 2009 exige medidas técnicas contra el acceso abusivo [13]. La Ley 23 de 1982 y la Decisión 351 de la Comunidad Andina amparan el código fuente, la base de datos y las interfaces como soporte lógico, y recuerdan que los apuntes cargados por la comunidad deben respetar derechos de terceros [14].",
    )
    body(
        doc,
        "Por esas razones, Konex no es un ejercicio aislado de programación. Es un emprendimiento académico con un problema local claramente delimitado, una solución técnica alcanzable en un ciclo de desarrollo y un valor diferenciador frente a WhatsApp, al aula de cursos y a las redes de propósito general: un solo canal, con roles institucionales y un agente que consulta lo que la propia plataforma ya organizó.",
    )

    # ------------------------------------------------------------------
    # 6. Objetivos
    # ------------------------------------------------------------------
    h1(doc, "6. Objetivos")
    h2(doc, "6.1. Objetivo general")
    body(
        doc,
        "Desarrollar Konex, una plataforma web colaborativa que centralice el intercambio de recursos académicos, la organización de grupos de estudio, la circulación de información institucional y la consulta inmediata de trámites entre estudiantes, docentes y directivos de UNIESPINAL.",
    )

    h2(doc, "6.2. Objetivos específicos")
    body(
        doc,
        "Analizar la arquitectura del software mediante Laravel, MySQL, Bootstrap y JavaScript, de modo que la plataforma sea segura, mantenible y adaptable a dispositivos móviles, con autenticación diferenciada para estudiante, docente y directivo.",
    )
    body(
        doc,
        "Diseñar un espacio centralizado que permita crear publicaciones, clasificar y compartir apuntes digitales por asignatura, y organizar grupos de estudio de forma ágil.",
    )
    body(
        doc,
        "Desarrollar un módulo de comunicación institucional que facilite la publicación de eventos y noticias, la gestión de solicitudes de horas sociales y la consulta de esa información mediante un agente de inteligencia artificial, en un entorno enfocado en la productividad académica.",
    )
    body(
        doc,
        "Definir la identidad corporativa y el posicionamiento del emprendimiento, de manera que el nombre, el logotipo, el eslogan, la misión, la visión y los valores comuniquen con claridad el propósito de Konex frente a las alternativas informales ya usadas por la comunidad.",
    )

    # ------------------------------------------------------------------
    # CAPÍTULO 1
    # ------------------------------------------------------------------
    center(doc, "CAPÍTULO 1", 12, True, False, 18, 4)
    center(doc, "Información general del emprendimiento", 12, True, False, 0, 16)

    # ------------------------------------------------------------------
    # 7. Nombre
    # ------------------------------------------------------------------
    h1(doc, "7. Nombre del emprendimiento")
    body(
        doc,
        "El emprendimiento se denomina Konex. El nombre es una marca corta, memorable y pronunciable en español. Se forma a partir de la idea de conexión y de nexo: la letra K evoca el apellido visual de una red, y el resto de la palabra remite a knex o connect, “conectar”. En el contexto universitario, Konex nombra el punto donde se encuentran estudiantes, docentes y directivos para colaborar, compartir material y consultar información institucional.",
    )
    body(
        doc,
        "La elección no es decorativa. Un nombre de plataforma debe decir, en una palabra, qué promete el producto. Konex promete vínculo, no entretenimiento. No compite con el nombre de un aula virtual de cursos ni con el de una red comercial. Se presenta como la red académica de UNIESPINAL: un canal propio, persistente y ordenado. En la interfaz, la marca se escribe KONEX en caja alta, con tracking amplio, y se acompaña del isotipo en forma de K construida por nodos y enlaces.",
    )
    body(
        doc,
        "El nombre comercial del emprendimiento es, por tanto, Konex. El título académico del proyecto, que describe el objeto de desarrollo, es Desarrollo de una plataforma web colaborativa para estudiantes, docentes y directivos en UNIESPINAL. Ambos se usan de manera complementaria: el primero identifica el producto; el segundo, el trabajo de grado y de emprendimiento.",
    )

    # ------------------------------------------------------------------
    # 8. Origen
    # ------------------------------------------------------------------
    h1(doc, "8. Origen de la idea")
    body(
        doc,
        "La idea no surge de un ejercicio abstracto de mercado, sino de la observación directa de la vida académica en UNIESPINAL. Los integrantes del equipo, estudiantes del Técnico Profesional en Programación Web, constataron que la coordinación de un semestre depende, en la práctica, de cadenas de WhatsApp y Telegram. En esos grupos se piden apuntes, se improvisan equipos, se reenvían avisos y se pregunta por horarios, eventos o el trámite de horas sociales. El archivo útil queda debajo de decenas de mensajes cotidianos. Quien llega tarde al chat llega tarde a la información [2], [3], [15].",
    )
    body(
        doc,
        "Esa experiencia se cruzó con la formación del programa. El plan de estudios exige construir software web real, con autenticación, base de datos y una interfaz usable. Al mismo tiempo, la literatura sobre aprendizaje colaborativo y sobre campus virtuales muestra que la mensajería instantánea puede apoyar la tutoría y el trabajo grupal, pero no constituye un ambiente virtual de aprendizaje ni un canal institucional auditable [1], [2], [7]. El aula Moodle de la institución resuelve el curso; no resuelve la comunidad ni el trámite [8], [10].",
    )
    body(
        doc,
        "De ese cruce nació Konex: tomar un problema local, visible y reiterado —la fragmentación de la comunicación académica e institucional— y convertirlo en un producto mínimo viable. La hipótesis de trabajo es operativa. Si existe una plataforma con roles, repositorio, grupos, publicaciones oficiales y un agente que consulte esa misma información, entonces la comunidad puede identificar compañeros, compartir material verificado y obtener una respuesta institucional sin depender de un chat saturado [4], [5], [9]. El origen de la idea es, en síntesis, la propia universidad vista como usuario, como contexto y como primer mercado del emprendimiento.",
    )

    # ------------------------------------------------------------------
    # 9. Problema
    # ------------------------------------------------------------------
    h1(doc, "9. Problema o necesidad")
    body(
        doc,
        "El problema que Konex busca resolver es la ausencia, en UNIESPINAL, de un entorno digital propio, centralizado y seguro para colaborar académicamente y para circular información institucional de forma oportuna. Un estudiante no construye su experiencia solo dentro del aula. Esa experiencia se forma en la interacción con pares, profesores y demás miembros de la institución. Hoy ese proceso es incierto, desorganizado y costoso en tiempo.",
    )
    body(
        doc,
        "El estudiante inicia el semestre a ciegas respecto a su red de apoyo: no cuenta con un canal único para identificar compañeros de asignatura, formar grupos de estudio o acceder a material verificado. Tampoco dispone de un medio inmediato para resolver dudas frecuentes sobre horarios, eventos, convocatorias, noticias o horas sociales. Debe preguntar en un chat, esperar una respuesta o enterarse cuando el plazo ya pasó. Docentes y directivos enfrentan el reverso del mismo problema: no tienen un medio formal y persistente para publicar eventos, noticias o decisiones sobre solicitudes, de modo que lo oficial termina dependiendo de cadenas informales [2], [7].",
    )
    body(
        doc,
        "Aunque la gran mayoría de la comunidad posee un dispositivo móvil y acceso a internet, no existe un medio eficaz diseñado para la dinámica de UNIESPINAL. Los apuntes, las guías, los talleres, los eventos y los trámites se dispersan en grupos de mensajería. En esas plataformas, el recurso de valor queda sepultado bajo un flujo irrelevante. Buscar un documento, coordinar una entrega, seguir una convocatoria o consultar un procedimiento se vuelve una tarea caótica [3], [15]. No hay un agente ni un canal que entregue esa información de forma clara; la persona tiene que buscar, preguntar o depender de quien “sepa” en ese momento.",
    )
    body(
        doc,
        "La necesidad se vuelve crítica cuando la falta de herramientas choca con la exigencia académica. Si el estudiante no consolida un equipo a tiempo, no encuentra el material de una evaluación o se entera tarde de un evento o de una solicitud de horas sociales, acumula una desventaja que luego se interpreta como falta de compromiso. En realidad, es el resultado de un entorno de comunicación fragmentado [1], [6]. La pregunta que orienta el emprendimiento es, por tanto, precisa: ¿cómo concentrar, en un solo producto web, la colaboración académica y la consulta institucional de UNIESPINAL, sin copiar una red comercial ni limitar el alcance al aula de un curso?",
    )
    body(
        doc,
        "Konex responde a esa pregunta con un producto mínimo viable. El alcance de esta etapa es el desarrollo y la validación del prototipo, no la puesta en producción institucional. La necesidad, sin embargo, ya está identificada y es suficiente para justificar un emprendimiento digital de base tecnológica.",
    )

    # ------------------------------------------------------------------
    # 10. Estado actual
    # ------------------------------------------------------------------
    h1(doc, "10. Estado actual")
    body(
        doc,
        "Konex es un emprendimiento ya creado, en etapa de producto mínimo viable. No se trata de una idea en papel. El software existe, se ejecuta en entorno de desarrollo y concentra los módulos priorizados para demostrar que la propuesta es viable. El estado actual corresponde a un prototipo funcional, validado en pruebas de aula y de laboratorio, todavía no desplegado como sistema misional de la institución.",
    )
    body(
        doc,
        "El producto se construyó con Laravel, MySQL, JavaScript y Bootstrap. La autenticación distingue tres roles: estudiante, docente y directivo. El estudiante colabora, comparte recursos, se une a grupos, publica, solicita horas sociales y consulta al agente. El docente crea y administra grupos de estudio. El directivo publica eventos y noticias, y resuelve las solicitudes de horas sociales. Esa separación no es cosmética: reproduce la estructura real de UNIESPINAL y evita que todos vean y publiquen lo mismo.",
    )

    add_table(
        doc,
        ["Módulo", "Estado", "Función en el emprendimiento"],
        [
            ["Autenticación y registro", "Operativo", "Ingreso seguro y creación de cuenta según rol."],
            ["Recuperación de acceso", "Operativo", "Código OTP y archivo de recuperación de la cuenta."],
            ["Grupos de estudio", "Operativo", "Creación por el docente y unión de estudiantes."],
            ["Repositorio de recursos", "Operativo", "Carga, clasificación y descarga de apuntes."],
            ["Publicaciones", "Operativo", "Avisos, reacciones y comentarios de la comunidad."],
            ["Chat interno", "Operativo", "Mensajería dentro de la plataforma, no en un chat externo."],
            ["Eventos y noticias", "Operativo", "Publicación institucional a cargo del directivo."],
            ["Horas sociales", "Operativo", "Solicitud del estudiante y revisión del directivo."],
            ["Agente de consulta", "Operativo", "Respuestas a partir de datos ya registrados en Konex."],
            ["Buzón interno", "Operativo", "Recepción de avisos y códigos de la propia plataforma."],
            ["Perfil y configuración", "Operativo", "Gestión de datos básicos del usuario."],
        ],
        "Tabla 2. Estado actual de los módulos del producto mínimo viable de Konex.",
    )

    body(
        doc,
        "El agente de inteligencia artificial no navega por internet ni entretiene. Consulta únicamente la información registrada en Konex: eventos, noticias, recursos verificados y el estado de las horas sociales. Si el dato no existe, declara que no lo tiene. Esa restricción es deliberada: protege al usuario, cumple el principio de minimización de la Ley 1581 de 2012 y diferencia el producto de un chatbot genérico [4], [9], [11], [12].",
    )
    body(
        doc,
        "En términos de emprendimiento, Konex se encuentra en fase de validación técnica y de definición de identidad. El prototipo permite demostrar el valor de uso. Aún no se ha fijado un esquema de comercialización ni un despliegue institucional. Esas decisiones corresponden a los capítulos de producto, marketing y análisis económico. El estado actual, no obstante, ya permite afirmar que el emprendimiento existe como software y no solo como propuesta.",
    )

    # ------------------------------------------------------------------
    # 11. Identidad
    # ------------------------------------------------------------------
    h1(doc, "11. Identidad corporativa")
    body(
        doc,
        "La identidad corporativa de Konex traduce, en signos visibles y en declaraciones de propósito, la promesa del emprendimiento: conectar a la comunidad de UNIESPINAL en un canal propio, ordenado y seguro. Se compone del logotipo, el eslogan, la misión, la visión y los valores. El sistema visual usa una paleta sobria, con granate institucional y carbón, para diferenciarse tanto del colorido de las redes comerciales como de la estética genérica de un aula Moodle.",
    )

    h2(doc, "11.1. Logotipo")
    body(
        doc,
        "El logotipo es un isotipo en forma de K construida como una red de nodos y enlaces. Cada vértice representa a una persona de la comunidad; cada arista, un vínculo académico o institucional. El trazo negro sostiene la estructura y el granate marca el cuerpo de la letra, de modo que la marca se lee a la vez como inicial de Konex y como mapa de conexiones. El wordmark se escribe KONEX en caja alta, con tracking amplio, para que la marca sea reconocible en la cabecera de la plataforma, en la portada y en piezas digitales futuras.",
    )

    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 10, 6)
    try:
        run = p.add_run()
        run.add_picture(LOGO, width=Inches(2.1))
    except Exception:
        run = p.add_run("[Logotipo de Konex]")
        font(run, 12, italic=True)

    cap = doc.add_paragraph()
    cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(cap, 0, 10)
    run = cap.add_run(
        "Figura 1. Logotipo de Konex. La letra K se construye como una red de nodos y enlaces."
    )
    font(run, 10)

    add_table(
        doc,
        ["Elemento", "Código", "Uso"],
        [
            ["Granate institucional", "#6E1F35", "Isotipo, acentos y acciones principales."],
            ["Granate secundario", "#8D2D46", "Estados de interacción y refuerzo de marca."],
            ["Carbón", "#0F172A", "Wordmark, texto principal y nodos de la red."],
            ["Gris", "#64748B", "Textos secundarios y ayudas de interfaz."],
            ["Blanco y fondo", "#FFFFFF / #F7F8FB", "Superficies de lectura y tarjetas."],
        ],
        "Tabla 3. Paleta cromática de la identidad visual de Konex.",
    )

    h2(doc, "11.2. Eslogan")
    body(
        doc,
        "El eslogan del emprendimiento es: Conecta, colabora y consulta. Las tres palabras resumen la promesa de valor y el recorrido del usuario. Conecta nombra el encuentro entre estudiantes, docentes y directivos. Colabora nombra el trabajo conjunto: grupos, apuntes, publicaciones y comentarios. Consulta nombra el acceso inmediato a eventos, noticias, trámites y horas sociales, incluido el uso del agente institucional. El eslogan es breve, se recuerda con facilidad y no promete entretenimiento ni una red social más.",
    )
    center(doc, "Konex. Conecta, colabora y consulta.", 12, True, True, 8, 12)

    h2(doc, "11.3. Misión")
    body(
        doc,
        "Concentrar, en un entorno web seguro y usable, la colaboración académica y la circulación de información institucional de UNIESPINAL, para que estudiantes, docentes y directivos compartan recursos verificados, organicen grupos de estudio, publiquen o consulten eventos y noticias, gestionen horas sociales y resuelvan dudas frecuentes sin depender de chats informales.",
    )

    h2(doc, "11.4. Visión")
    body(
        doc,
        "En 2028, Konex será el canal digital de referencia de la comunidad de UNIESPINAL: un punto de encuentro cotidiano, reconocible por su marca y por la confiabilidad de su información, capaz de sostener la colaboración entre roles y de ofrecer consulta institucional inmediata, sin sustituir el aula de cursos y sin copiar el modelo de las redes comerciales.",
    )

    h2(doc, "11.5. Valores corporativos")
    body(
        doc,
        "Los valores orientan tanto el diseño del software como la conducta del emprendimiento. No se declaran como adornos: cada uno se traduce en una decisión visible del producto.",
    )

    add_table(
        doc,
        ["Valor", "Traducción en el producto"],
        [
            [
                "Colaboración",
                "El conocimiento se construye en grupo. Por eso existen recursos compartidos, grupos de estudio, publicaciones, comentarios y roles que no aíslan al usuario.",
            ],
            [
                "Veracidad",
                "La información institucional se trata como un hecho consultable. El agente responde solo con datos registrados en Konex y declara cuando no los tiene.",
            ],
            [
                "Oportunidad",
                "Un aviso, un evento o una solicitud deben llegar a tiempo. La plataforma concentra lo oficial para que no dependa de quien reenvía un mensaje.",
            ],
            [
                "Confidencialidad",
                "Se recogen solo los datos necesarios. Las credenciales se protegen y el agente no guarda historiales invasivos, en línea con la Ley 1581 de 2012.",
            ],
            [
                "Accesibilidad",
                "El producto se usa desde el navegador, en computador o en celular, sin exigir una aplicación nativa en esta etapa.",
            ],
            [
                "Productividad",
                "Konex no compite por el tiempo de ocio. Su métrica de éxito es que la persona encuentre un recurso, un grupo o una respuesta oficial con menos fricción.",
            ],
        ],
        "Tabla 4. Valores corporativos de Konex y su expresión en el producto.",
    )

    body(
        doc,
        "Con esta identidad, el Capítulo 1 cierra el marco general del emprendimiento. Queda establecido el nombre, el origen, el problema, el estado real del prototipo y el sistema de marca. El Capítulo 2 describirá el producto o servicio; el Capítulo 3, la estrategia de marketing digital; y el Capítulo 4, el análisis económico.",
    )

    # ------------------------------------------------------------------
    # Referencias (necesarias por las citas IEEE de este avance)
    # ------------------------------------------------------------------
    h1(doc, "30. Referencias")
    refs = [
        [
            ('M. del P. García-Chitiva, "Aprendizaje colaborativo, mediado por internet, en procesos de educación superior," ', False),
            ("Rev. Electrón. Educare", True),
            (", vol. 25, no. 2, pp. 1-19, 2021, doi: 10.15359/ree.25-2.23.", False),
        ],
        [
            ('G. A. Espinel Rubio, C. A. Hernández Suárez y R. Prada Núñez, "WhatsApp vs. campus virtual institucional en tiempos de covid-19. Percepción de los estudiantes de comunicación social," ', False),
            ("Rev. Boletín Redipe", True),
            (", vol. 10, no. 10, pp. 519-533, oct. 2021, doi: 10.36260/rbr.v10i10.1510.", False),
        ],
        [
            ('M. G. Veytia Bucheli y F. A. Bastidas Terán, "WhatsApp como recurso para el trabajo grupal en estudiantes universitarios," ', False),
            ("Apertura", True),
            (", vol. 12, no. 2, pp. 74-93, 2020, doi: 10.32870/ap.v12n2.1911.", False),
        ],
        [
            ('M. M. Echeverri Torres y R. Manjarrés-Betancur, "Asistente virtual académico utilizando tecnologías cognitivas de procesamiento de lenguaje natural," ', False),
            ("Rev. Politécnica", True),
            (", vol. 16, no. 31, pp. 85-96, 2020, doi: 10.33571/rpolitec.v16n31a7.", False),
        ],
        [
            ('J. Q. Pérez, T. Daradoumis y J. M. Marquès Puig, "Rediscovering the use of chatbots in education: A systematic literature review," ', False),
            ("Comput. Appl. Eng. Educ.", True),
            (", vol. 28, no. 6, pp. 1549-1565, 2020, doi: 10.1002/cae.22326.", False),
        ],
        [
            ('N. Hernández-Sellés, P. C. Muñoz-Carril y M. González-Sanmamed, "Computer-supported collaborative learning: An analysis of the relationship between interaction, emotional support and online collaborative tools," ', False),
            ("Comput. Educ.", True),
            (", vol. 138, pp. 1-12, 2019, doi: 10.1016/j.compedu.2019.04.012.", False),
        ],
        [
            ('B. Suárez Lantarón, "WhatsApp como herramienta de apoyo a la tutoría," ', False),
            ("REDU. Rev. Docencia Univ.", True),
            (", vol. 15, no. 2, pp. 193-210, 2017, doi: 10.4995/redu.2017.6941.", False),
        ],
        [
            ('M. F. Morado y S. Ocampo-Hernández, "Una experiencia de acompañamiento tecno-pedagógico para la construcción de entornos virtuales de aprendizaje en educación superior," ', False),
            ("Rev. Educ.", True),
            (", vol. 43, no. 1, 2019, doi: 10.15517/revedu.v43i1.28457.", False),
        ],
        [
            ('C. W. Okonkwo y A. Ade-Ibijola, "Chatbots applications in education: A systematic review," ', False),
            ("Comput. Educ.: Artif. Intell.", True),
            (", vol. 2, p. 100033, 2021, doi: 10.1016/j.caeai.2021.100033.", False),
        ],
        [
            ('D. Turnbull, R. Chugh y J. Luck, "Learning management systems: A review of the research published in the last 40 years," ', False),
            ("J. Comput. Educ.", True),
            (", 2021.", False),
        ],
        [
            ('O. Zawacki-Richter, V. I. Marín, M. Bond y F. Gouverneur, "Systematic review of research on artificial intelligence applications in higher education—where are the educators?," ', False),
            ("Int. J. Educ. Technol. High. Educ.", True),
            (", vol. 16, art. no. 39, 2019.", False),
        ],
        [
            ("Congreso de la República de Colombia, Ley 1581 de 2012, por la cual se dictan disposiciones generales para la protección de datos personales, ", False),
            ("Diario Oficial", True),
            (" No. 48.587, 17 oct. 2012.", False),
        ],
        [
            ("Congreso de la República de Colombia, Ley 1273 de 2009, de la protección de la información y de los datos, ", False),
            ("Diario Oficial", True),
            (" No. 47.223, 5 ene. 2009.", False),
        ],
        [
            ("Congreso de la República de Colombia, Ley 23 de 1982, sobre derechos de autor, ", False),
            ("Diario Oficial", True),
            (" No. 35.949, 28 ene. 1982.", False),
        ],
        [
            ('N. A. Guiñez-Cabrera y K. Mansilla-Obando, "WhatsApp Web con fines académicos en tiempos de la covid-19," ', False),
            ("Apertura", True),
            (", vol. 13, no. 2, 2021, doi: 10.32870/ap.v13n2.2084.", False),
        ],
    ]
    for i, parts in enumerate(refs, start=1):
        ref_line(doc, i, parts)

    doc.save(OUT)
    print(OUT)


if __name__ == "__main__":
    build()
