# -*- coding: utf-8 -*-
"""Proyecto de emprendimiento Konex completo, normas IEEE 2025."""

from docx import Document
from docx.enum.section import WD_SECTION
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING, WD_TAB_ALIGNMENT, WD_TAB_LEADER
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Cm, Inches, Mm, Pt, RGBColor

OUT = r"C:\Users\nico1\Downloads\Konex_Proyecto_Emprendimiento_IEEE.docx"
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


def body(doc, text, first=0.17):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(p, 0, 6, first)
    run = p.add_run(text)
    font(run, 10)
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
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 10, 8)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    font(run, 10, bold=True)
    return p


def h2(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 8, 4)
    p.paragraph_format.keep_with_next = True
    run = p.add_run(text)
    font(run, 10, bold=True, italic=True)
    return p


def h3(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 12, 0)
    run = p.add_run(text)
    font(run, 12, italic=True)
    return p


def equation(doc, text, number):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 10, 10, 0)
    run = p.add_run(text)
    font(run, 10, italic=True)
    run2 = p.add_run("    (" + str(number) + ")")
    font(run2, 10)
    return p


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
    font(run, 9, bold=True)

    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = True
    for i, header in enumerate(headers):
        cell_text(table.rows[0].cells[i], header, 8, True, "center")
        shade_cell(table.rows[0].cells[i], "D9D9D9")
    for r, row in enumerate(rows):
        for c, value in enumerate(row):
            align = "center" if c == 0 else "left"
            cell_text(table.rows[r + 1].cells[c], value, 8, False, align)

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
        font(run, 8, italic=italic)
        first = False


def build():
    doc = Document()
    first = doc.sections[0]
    first.page_width = Inches(8.5)
    first.page_height = Inches(11)
    first.top_margin = Inches(0.75)
    first.bottom_margin = Inches(0.75)
    first.left_margin = Inches(0.75)
    first.right_margin = Inches(0.75)
    page_number_footer(first)

    cover_line(
        doc,
        "Desarrollo de una plataforma web colaborativa para estudiantes, docentes y directivos en UNIESPINAL",
        20,
        True,
        6,
        10,
    )
    cover_line(
        doc,
        "Eddy Santiago Vargas Tique, Nicolás Esteban Martínez González y Damián Jesús Barbosa Álvarez",
        11,
        False,
        0,
        6,
    )
    cover_line(doc, "Institución Universitaria de El Espinal (UNIESPINAL)", 10, False, 0, 0)
    cover_line(doc, "Facultad de Ingeniería y Ciencias Agroindustriales", 10, False, 0, 0)
    cover_line(doc, "Técnico Profesional en Programación Web", 10, False, 0, 0)
    cover_line(doc, "Proyecto de emprendimiento — Konex", 10, False, 0, 0)
    cover_line(doc, "Espinal, Tolima, Colombia — Noviembre de 2026", 10, False, 0, 4)
    cover_line(
        doc,
        "Asesores: Álvaro José Feria Rodríguez; Jeniffer Julieth Palencia Quezada",
        9,
        False,
        0,
        10,
    )

    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 4, 4)
    run = p.add_run("Abstract")
    font(run, 10, True, True)
    body(
        doc,
        "Este documento presenta Konex, un emprendimiento digital consistente en una plataforma web colaborativa para estudiantes, docentes y directivos de UNIESPINAL. El producto concentra grupos de estudio, recursos académicos, eventos, noticias, horas sociales y un agente de consulta institucional. Se describen la identidad del emprendimiento, el servicio, la estrategia de marketing digital y el análisis económico. El alcance es el prototipo funcional, no la implementación oficial en la institución.",
        first=0.17,
    )
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(p, 0, 10)
    run = p.add_run(
        "Palabras clave: Konex, emprendimiento digital, plataforma colaborativa, UNIESPINAL, marketing digital, punto de equilibrio."
    )
    font(run, 9, italic=True)

    body_sec = doc.add_section(WD_SECTION.CONTINUOUS)
    body_sec.page_width = Inches(8.5)
    body_sec.page_height = Inches(11)
    body_sec.top_margin = Inches(0.75)
    body_sec.bottom_margin = Inches(0.75)
    body_sec.left_margin = Inches(0.75)
    body_sec.right_margin = Inches(0.75)
    set_two_columns(body_sec)
    body_sec.header.is_linked_to_previous = True
    body_sec.footer.is_linked_to_previous = True

    h1(doc, "I. INTRODUCCIÓN")
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
        "Konex nace como respuesta a esa necesidad. Es un emprendimiento digital de Eddy Santiago Vargas Tique, Nicolás Esteban Martínez González y Damián Jesús Barbosa Álvarez, del programa de Técnico Profesional en Programación Web. La propuesta consiste en una plataforma web colaborativa que reúne, en un solo producto, autenticación por roles, repositorio de recursos, grupos de estudio, publicaciones, eventos, noticias, gestión de horas sociales y un agente de inteligencia artificial alimentado por información institucional ya centralizada. A diferencia de las redes comerciales, el producto no se diseña para retener al usuario con entretenimiento, sino para la productividad académica y la circulación oportuna de datos oficiales [4], [5].",
    )
    body(
        doc,
        "El presente documento desarrolla el proyecto de emprendimiento en el orden pedido por la asignatura. La introducción sitúa el tema. La justificación explica la pertinencia social, práctica, técnica y normativa. Los objetivos declaran el propósito del producto. El Capítulo 1 presenta el nombre, el origen, el problema, el estado actual y la identidad corporativa. El Capítulo 2 describe el producto, su ficha técnica, la innovación y el valor agregado. El Capítulo 3 formula la estrategia de marketing digital. El Capítulo 4 analiza costos, precio y punto de equilibrio. El cierre recoge conclusiones, referencias y anexos.",
    )

    # ------------------------------------------------------------------
    # 5. Justificación
    # ------------------------------------------------------------------
    h1(doc, "II. JUSTIFICACIÓN")
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
    h1(doc, "III. OBJETIVOS")
    h2(doc, "A. Objetivo general")
    body(
        doc,
        "Desarrollar Konex, una plataforma web colaborativa que centralice el intercambio de recursos académicos, la organización de grupos de estudio, la circulación de información institucional y la consulta inmediata de trámites entre estudiantes, docentes y directivos de UNIESPINAL.",
    )

    h2(doc, "B. Objetivos específicos")
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
    h1(doc, "IV. INFORMACIÓN GENERAL DEL EMPRENDIMIENTO")
    add_table(
        doc,
        ["Nombre", "Rol"],
        [
            ["Eddy Santiago Vargas Tique", "Investigador"],
            ["Nicolás Esteban Martínez González", "Investigador principal y desarrollador"],
            ["Damián Jesús Barbosa Álvarez", "Desarrollador e investigador"],
            ["Álvaro José Feria Rodríguez", "Asesor"],
            ["Jeniffer Julieth Palencia Quezada", "Asesora"],
        ],
        "Tabla 1. Investigadores y asesores del emprendimiento Konex.",
    )
    h2(doc, "A. Nombre del emprendimiento")
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
    h2(doc, "B. Origen de la idea")
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
    h2(doc, "C. Problema o necesidad")
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
    h2(doc, "D. Estado actual")
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
    h2(doc, "E. Identidad corporativa")
    body(
        doc,
        "La identidad corporativa de Konex traduce, en signos visibles y en declaraciones de propósito, la promesa del emprendimiento: conectar a la comunidad de UNIESPINAL en un canal propio, ordenado y seguro. Se compone del logotipo, el eslogan, la misión, la visión y los valores. El sistema visual usa una paleta sobria, con granate institucional y carbón, para diferenciarse tanto del colorido de las redes comerciales como de la estética genérica de un aula Moodle.",
    )

    h2(doc, "1) Logotipo")
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

    h2(doc, "2) Eslogan")
    body(
        doc,
        "El eslogan del emprendimiento es: Conecta, colabora y consulta. Las tres palabras resumen la promesa de valor y el recorrido del usuario. Conecta nombra el encuentro entre estudiantes, docentes y directivos. Colabora nombra el trabajo conjunto: grupos, apuntes, publicaciones y comentarios. Consulta nombra el acceso inmediato a eventos, noticias, trámites y horas sociales, incluido el uso del agente institucional. El eslogan es breve, se recuerda con facilidad y no promete entretenimiento ni una red social más.",
    )
    center(doc, "Konex. Conecta, colabora y consulta.", 12, True, True, 8, 12)

    h2(doc, "3) Misión")
    body(
        doc,
        "Concentrar, en un entorno web seguro y usable, la colaboración académica y la circulación de información institucional de UNIESPINAL, para que estudiantes, docentes y directivos compartan recursos verificados, organicen grupos de estudio, publiquen o consulten eventos y noticias, gestionen horas sociales y resuelvan dudas frecuentes sin depender de chats informales.",
    )

    h2(doc, "4) Visión")
    body(
        doc,
        "En 2028, Konex será el canal digital de referencia de la comunidad de UNIESPINAL: un punto de encuentro cotidiano, reconocible por su marca y por la confiabilidad de su información, capaz de sostener la colaboración entre roles y de ofrecer consulta institucional inmediata, sin sustituir el aula de cursos y sin copiar el modelo de las redes comerciales.",
    )

    h2(doc, "5) Valores corporativos")
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
        "Con esta identidad, el Capítulo 1 cierra el marco general del emprendimiento. Queda establecido el nombre, el origen, el problema, el estado real del prototipo y el sistema de marca. El Capítulo 2 describe el producto o servicio.",
    )

    # ------------------------------------------------------------------
    # CAPÍTULO 2
    # ------------------------------------------------------------------
    h1(doc, "V. PRODUCTO O SERVICIO")
    h2(doc, "A. Descripción")
    body(
        doc,
        "Konex es un servicio digital, no un bien físico. Se ofrece como una plataforma web colaborativa de uso institucional que concentra, en un solo entorno, la vida académica extra-aula de UNIESPINAL. El usuario entra con correo institucional, elige o recibe un rol (estudiante, docente o directivo) y encuentra grupos de estudio, un repositorio de apuntes, un muro de publicaciones, chat interno, eventos, noticias, solicitudes de horas sociales y un agente de consulta que responde solo con información ya registrada en el sistema [1], [2], [4].",
    )
    body(
        doc,
        "El servicio se consume desde el navegador, en computador o en celular, sin instalar una aplicación nativa. Esa decisión reduce la fricción de adopción: la comunidad ya estudia con el teléfono y no necesita otro icono en la pantalla. El producto no pretende sustituir el aula virtual de cursos ni competir con WhatsApp como mensajería personal. Su lugar es el que hoy nadie cubre con rigor: la colaboración entre pares, la circulación de lo oficial y la consulta inmediata de un trámite o de un evento [2], [8], [10].",
    )
    body(
        doc,
        "Desde el modelo de negocio, Konex se vende como software como servicio (SaaS) a la institución. Los estudiantes y los docentes son usuarios; el cliente que paga es la universidad o, en una etapa posterior, otra institución de educación superior. La licencia anual habilita cuentas ilimitadas de la comunidad, los módulos institucionales y el agente de consulta. Ese esquema evita cobrarle al estudiante por un canal que debería ser parte de su vida académica y, al mismo tiempo, da al emprendimiento un ingreso previsible [16].",
    )

    h2(doc, "B. Ficha técnica")
    body(
        doc,
        "La ficha técnica resume cómo está hecho el producto, de qué se compone y cómo funciona. Konex se desarrolló con Laravel (patrón Modelo-Vista-Controlador), MySQL como base de datos relacional, Bootstrap y JavaScript en la interfaz, y un servicio de consulta institucional que interpreta preguntas en lenguaje natural y las resuelve contra los datos ya almacenados. La autenticación protege sesiones, distingue roles y aplica reglas de acceso: el docente crea grupos; el directivo publica eventos y noticias y resuelve horas sociales; el estudiante colabora, comparte, solicita y consulta [4], [9], [10].",
    )

    add_table(
        doc,
        ["Componente", "Especificación"],
        [
            ["Tipo de producto", "Plataforma web colaborativa (SaaS institucional)."],
            ["Usuarios", "Estudiante, docente y directivo, autenticados con correo institucional."],
            ["Acceso", "Navegador web, interfaz responsiva (computador y celular)."],
            ["Backend", "Laravel, patrón MVC, autenticación, sesiones y protección CSRF."],
            ["Base de datos", "MySQL. Catálogos de roles, programas, asignaturas, grupos, recursos, eventos y trámites."],
            ["Frontend", "Bootstrap y JavaScript. Actualizaciones de contenido sin recargar toda la página."],
            ["Módulos", "Registro, recuperación, grupos, recursos, publicaciones, chat, institucional, horas sociales, agente y buzón."],
            ["Agente de IA", "Consulta eventos, noticias, recursos verificados y estado de horas sociales. No navega por internet."],
            ["Seguridad", "Roles, cifrado de credenciales, minimización de datos (Ley 1581 de 2012) [12]."],
            ["Alcance actual", "Producto mínimo viable funcional. Aún no está en producción institucional."],
        ],
        "Tabla 5. Ficha técnica de Konex.",
    )

    body(
        doc,
        "El funcionamiento es lineal para el usuario y relacional para el sistema. Tras el ingreso, el estudiante ve el inicio, se une a un grupo con un código, carga o descarga un recurso clasificado por asignatura, comenta una publicación, pregunta al agente o radica una evidencia de horas sociales. El directivo publica un evento o una noticia y cambia el estado de una solicitud. Cada acción queda en tablas vinculadas: el recurso no se pierde en un hilo de chat; el evento no depende de un reenvío. Esa persistencia es la base técnica del servicio y lo que el agente consulta cuando alguien pregunta [4], [5].",
    )

    h2(doc, "C. Innovación")
    body(
        doc,
        "La innovación de Konex no está en inventar la web ni el chatbot. Está en combinar, para una institución de ciudad intermedia, tres capas que hoy viven separadas: colaboración entre pares, canal oficial y consulta inmediata. WhatsApp cubre la urgencia. El aula Moodle cubre el curso. Ninguna de las dos cubre, al mismo tiempo, el grupo de estudio, el repositorio verificado, el evento institucional, la hora social y una pregunta en lenguaje natural [2], [8], [10].",
    )
    body(
        doc,
        "El primer aspecto novedoso es el diseño por roles reales de UNIESPINAL. No todos publican lo mismo. Esa regla, simple en apariencia, evita que lo oficial se mezcle con el ruido y da a docentes y directivos un lugar visible para emitir información. El segundo es el repositorio clasificado por asignatura y por verificación: el archivo deja de ser un adjunto efímero. El tercero es el agente de inteligencia artificial restringido. No conversa de cualquier tema ni rastrea al usuario. Responde con datos de Konex o declara que no los tiene. Esa limitación es una innovación de producto porque convierte la IA en un mostrador institucional, no en un entretenimiento [4], [5], [9], [11].",
    )
    body(
        doc,
        "El cuarto aspecto es el enfoque de producto mínimo viable. En lugar de construir una aplicación nativa, un campus completo o un marketplace, se entregó primero lo estrictamente necesario para probar la hipótesis de valor: autenticación, grupos, recursos, institucional, horas sociales y agente [16]. Esa decisión reduce costo, tiempo y riesgo, y deja abierta la evolución hacia un despliegue institucional o hacia otras IES del Tolima.",
    )

    h2(doc, "D. Valor agregado")
    body(
        doc,
        "El valor agregado se mide por contraste con las alternativas que la comunidad ya usa. Frente a WhatsApp o Telegram, Konex no sepulta el archivo ni mezcla lo académico con lo cotidiano: busca, filtra y conserva. Frente al aula virtual de cursos, no pretende calificar ni montar una cátedra: organiza la comunidad y el trámite. Frente a las redes comerciales, no vende atención ni retiene con algoritmos de ocio: su métrica es que la persona encuentre un recurso, un grupo o una respuesta oficial [2], [8], [10].",
    )

    add_table(
        doc,
        ["Alternativa", "Lo que resuelve", "Lo que Konex agrega"],
        [
            [
                "WhatsApp / Telegram",
                "Inmediatez y grupo informal.",
                "Persistencia, búsqueda, roles y un canal auditable.",
            ],
            [
                "Aula virtual (Moodle)",
                "Curso, tareas y contenidos de cátedra.",
                "Comunidad extra-aula, grupos horizontales y trámites.",
            ],
            [
                "Redes sociales comerciales",
                "Alcance y entretenimiento.",
                "Productividad académica y datos institucionales verificables.",
            ],
            [
                "Ventanilla o correo institucional",
                "Trámite formal, con horario.",
                "Consulta inmediata del agente y seguimiento de horas sociales.",
            ],
        ],
        "Tabla 6. Valor agregado de Konex frente a las alternativas existentes.",
    )

    body(
        doc,
        "Ese diferencial es el argumento de venta a la institución: no se compra un chat más, se compra un orden. El estudiante gana tiempo. El docente gana un grupo que no se deshace cuando cambia el semestre. El directivo gana un medio para publicar y para resolver solicitudes. El emprendimiento gana una promesa clara, repetible en otras universidades pequeñas y medianas que hoy también viven en el chat [1], [7], [16].",
    )

    # ------------------------------------------------------------------
    # CAPÍTULO 3
    # ------------------------------------------------------------------
    h1(doc, "VI. ESTRATEGIA DE MARKETING DIGITAL")
    h2(doc, "A. Público objetivo")
    body(
        doc,
        "El público objetivo de Konex se define en dos capas, porque el usuario y el cliente no son la misma persona [17]. La primera capa es la comunidad de UNIESPINAL: estudiantes de los programas presenciales, docentes y directivos que hoy coordinan su vida académica por mensajería instantánea. Esa capa usa el producto. La segunda capa es el decisor institucional: coordinaciones, vicerrectorías o áreas de sistemas que pueden contratar una licencia anual. Esa capa paga el servicio.",
    )
    body(
        doc,
        "En el corto plazo, el mercado primario es UNIESPINAL, en El Espinal, Tolima. El segmento de usuarios más amplio es el estudiante de primeros semestres, que llega sin red de apoyo y pierde más tiempo buscando material y trámites. El segmento de mayor poder de difusión es el docente, porque un grupo de asignatura creado por él arrastra a decenas de estudiantes. El segmento de mayor poder de compra es el directivo, porque habilita eventos, noticias y horas sociales, y porque representa a la institución ante un contrato [2], [17].",
    )
    body(
        doc,
        "En el mediano plazo, el mercado se amplía a instituciones de educación superior de tamaño similar en el Tolima y el norte del Huila, que ya tienen aula de cursos pero no un canal colaborativo propio. No se pretende competir con Moodle ni con las grandes redes. Se pretende ocupar el hueco local que esas herramientas dejan [10], [16].",
    )

    add_table(
        doc,
        ["Segmento", "Rol", "Qué busca", "Qué está dispuesto a hacer"],
        [
            [
                "Estudiante de UNIESPINAL",
                "Usuario",
                "Apuntes, grupo y respuestas rápidas.",
                "Registrarse y usar el producto si le ahorra tiempo.",
            ],
            [
                "Docente",
                "Usuario y prescriptor",
                "Un grupo ordenado y material que no se pierda.",
                "Crear el grupo y pedir a sus estudiantes que entren.",
            ],
            [
                "Directivo",
                "Usuario y decisor",
                "Publicar lo oficial y gestionar horas sociales.",
                "Autorizar la prueba y, luego, la licencia.",
            ],
            [
                "Otras IES regionales",
                "Cliente futuro",
                "Un canal propio, liviano y asequible.",
                "Contratar un plan piloto de una facultad.",
            ],
        ],
        "Tabla 7. Público objetivo de Konex.",
    )

    h2(doc, "B. Buyer persona")
    body(
        doc,
        "El buyer persona resume al cliente ideal en un perfil concreto, para que el mensaje, el canal y el producto no se diseñen “para todo el mundo” [17]. Konex trabaja con tres perfiles. El primero describe al usuario más frecuente. El segundo, al prescriptor. El tercero, a quien firma o autoriza la compra.",
    )

    add_table(
        doc,
        ["Dato", "Camila (estudiante)", "Andrés (docente)", "Martha (directiva)"],
        [
            ["Edad", "19 años", "38 años", "46 años"],
            ["Rol", "Técnico profesional, 2.° semestre", "Docente de programación", "Coordinación académica"],
            ["Dispositivo", "Celular Android, datos limitados", "Portátil y celular", "Portátil institucional"],
            ["Frustración", "Se le pierde el PDF en el chat y se entera tarde de los avisos.", "Reenvía la misma guía cada semana y el grupo se desordena.", "Publica un evento y no sabe quién se enteró."],
            ["Objetivo", "Armar grupo, bajar apuntes y preguntar por horas sociales.", "Tener un espacio por asignatura, sin ruido.", "Un canal oficial, trazable y simple."],
            ["Canal que usa", "WhatsApp, Instagram, TikTok", "Correo, WhatsApp y aula Moodle", "Correo institucional y reuniones"],
            ["Qué la convence", "Que funcione en el celular y que sus compañeros ya estén.", "Que tarde menos de diez minutos en crear el grupo.", "Que no ponga en riesgo datos ni replique un chat más."],
            ["Mensaje", "Deja de buscar el archivo entre cien mensajes.", "Un grupo que sí queda.", "Lo oficial, en un solo lugar."],
        ],
        "Tabla 8. Buyer persona de Konex.",
    )

    body(
        doc,
        "La compra institucional la cierra Martha, pero la adopción la gana Camila. Si el estudiante no entra, el contrato no se renueva. Por eso la comunicación digital habla primero al dolor cotidiano (el archivo perdido, el aviso tarde) y, en paralelo, ofrece a la directiva evidencia de orden, roles y protección de datos [12], [17].",
    )

    h2(doc, "C. Canales digitales")
    body(
        doc,
        "Los canales se eligen donde ya está el público, no donde el equipo preferiría estar [18]. Konex usa un mix corto: Instagram y TikTok para atraer estudiantes; WhatsApp como puente de invitación, no como producto; correo institucional y LinkedIn para docentes y directivos; y la propia landing de Konex como destino de conversión. El aula Moodle y las carteleras físicas se usan solo como refuerzo local, no como canal principal.",
    )

    add_table(
        doc,
        ["Canal", "Audiencia", "Objetivo", "Justificación"],
        [
            [
                "Instagram",
                "Estudiantes",
                "Reconocimiento de marca y tráfico a la landing.",
                "Es el canal visual cotidiano del segmento de 16 a 24 años.",
            ],
            [
                "TikTok",
                "Estudiantes",
                "Alcance orgánico con videos cortos del problema.",
                "El formato de 15 a 30 segundos explica el dolor mejor que un folleto.",
            ],
            [
                "WhatsApp",
                "Estudiantes y docentes",
                "Invitación al registro, no almacenamiento de archivos.",
                "Hoy es el canal real; se usa para sacar a la gente del chat, no para dejarla ahí.",
            ],
            [
                "Correo institucional",
                "Docentes y directivos",
                "Demo, piloto y propuesta de licencia.",
                "Es el medio formal de la universidad y deja trazabilidad.",
            ],
            [
                "Landing Konex",
                "Todos",
                "Registro, inicio de sesión y prueba del producto.",
                "El producto es el mejor argumento: se usa, no solo se anuncia.",
            ],
            [
                "LinkedIn",
                "Directivos y otras IES",
                "Posicionamiento B2B y contactos de expansión.",
                "El cliente futuro no está en TikTok; está en redes profesionales [18].",
            ],
        ],
        "Tabla 9. Canales digitales seleccionados.",
    )

    h2(doc, "D. Marketing de contenidos")
    body(
        doc,
        "La estrategia de contenidos no vende descuentos. Muestra el problema, enseña el uso y prueba que Konex ordena lo que el chat desordena [19]. El eje narrativo es el eslogan: Conecta, colabora y consulta. Cada pieza cubre una de esas tres promesas. El tono es cercano, en español claro, sin jerga de marketing y sin promesas de entretenimiento. Se publica para ser útil: un consejo de cómo armar un grupo, un aviso de cómo radicar horas sociales, una demostración de una pregunta al agente.",
    )
    body(
        doc,
        "El embudo es simple. En el tramo de atracción, videos y carruseles del dolor (“el PDF que se perdió”). En el tramo de consideración, tutoriales de un minuto y comparaciones honestas con WhatsApp y con el aula. En el tramo de conversión, invitación a crear cuenta y a pedir una demo institucional. En el tramo de fidelización, contenidos hechos dentro de Konex (eventos reales, recursos destacados) para que la plataforma se vuelva el medio, no solo el anuncio [18], [19].",
    )

    h2(doc, "E. Publicaciones")
    body(
        doc,
        "Se proponen piezas concretas, listas para adaptar a Instagram, TikTok o correo. Todas remiten a la landing o al registro. El llamado a la acción es uno solo por pieza, para no diluir el mensaje [19].",
    )

    add_table(
        doc,
        ["N.°", "Pieza", "Canal", "Mensaje y llamado"],
        [
            [
                "1",
                "Carrusel de 5 láminas",
                "Instagram",
                "“Se te perdió el PDF otra vez.” Lámina final: entra a Konex y búscalo por asignatura.",
            ],
            [
                "2",
                "Video de 20 s",
                "TikTok / Reels",
                "Un estudiante pregunta al agente: “¿Cuándo es el próximo evento?” Respuesta en pantalla. CTA: consúltalo en Konex.",
            ],
            [
                "3",
                "Historia con encuesta",
                "Instagram",
                "“¿Dónde se te pierden los apuntes? Chat / Drive / No sé.” Sticker de enlace al registro.",
            ],
            [
                "4",
                "Tutorial de 60 s",
                "Instagram / YouTube Shorts",
                "Cómo unirse a un grupo con código. CTA: pídele el código a tu docente.",
            ],
            [
                "5",
                "Post de autoridad",
                "LinkedIn / correo",
                "Tres diferencias entre un chat y un canal institucional. CTA: agenda una demo de 15 minutos.",
            ],
            [
                "6",
                "Antes y después",
                "TikTok",
                "Pantalla de un chat saturado frente a un recurso clasificado en Konex.",
            ],
            [
                "7",
                "Aviso de trámite",
                "Instagram y correo",
                "Cómo radicar horas sociales con evidencia. CTA: entra a Horas sociales.",
            ],
            [
                "8",
                "Cita de un docente",
                "Instagram",
                "“Dejé de reenviar la guía cada lunes.” CTA: crea tu grupo en Konex.",
            ],
        ],
        "Tabla 10. Propuestas de contenido digital.",
    )

    h2(doc, "F. Calendario editorial")
    body(
        doc,
        "El calendario organiza cuatro semanas tipo, repetibles durante un semestre. La cadencia es de cuatro a seis piezas por semana, con un día de pauta paga (Meta Ads) enfocado en estudiantes de UNIESPINAL y un día de contacto B2B. Se reserva un cupo para contenidos de oportunidad (un evento real, una convocatoria) que se publican el mismo día [18], [19].",
    )

    add_table(
        doc,
        ["Día", "Semana 1", "Semana 2", "Semana 3", "Semana 4"],
        [
            ["Lunes", "Carrusel del problema", "Tutorial de grupos", "Carrusel de horas sociales", "Resultados de la encuesta"],
            ["Martes", "Reel del agente", "Historia con encuesta", "Reel antes/después", "Reel de un evento real"],
            ["Miércoles", "Pauta Instagram (estudiantes)", "Pauta TikTok", "Pauta remarketing a la landing", "Pauta de demo institucional"],
            ["Jueves", "Correo a docentes", "Post LinkedIn", "Correo a coordinaciones", "Seguimiento de demos"],
            ["Viernes", "Cita de un usuario", "Tutorial de recursos", "Aviso de un evento Konex", "Resumen semanal en historias"],
            ["Sábado", "Repost en WhatsApp (invitación)", "—", "Repost de la mejor pieza", "—"],
        ],
        "Tabla 11. Calendario editorial tipo de cuatro semanas.",
    )

    h2(doc, "G. Comercialización digital")
    body(
        doc,
        "La venta no ocurre en un carrito de supermercado. El recorrido es B2B2C: se convence al usuario para demostrar valor y se cierra con la institución [16], [17]. El proceso tiene cinco pasos. Primero, la landing y las redes llevan al registro gratuito de prueba en UNIESPINAL. Segundo, el uso de grupos, recursos y agente genera evidencia (tiempo de búsqueda, consultas resueltas, solicitudes radicadas). Tercero, se ofrece una demo de quince minutos a docentes y directivos. Cuarto, se entrega una propuesta de plan piloto o plan campus. Quinto, se firma la licencia anual y se habilita el entorno de la institución, con roles y dominio de correo propios.",
    )
    body(
        doc,
        "Los canales de venta son el correo institucional, la reunión virtual, LinkedIn y, cuando exista, un formulario de “solicitar piloto” en la landing. No se vende el producto dentro de TikTok. TikTok atrae; la landing y el correo cierran [18]. En una etapa posterior, el mismo proceso se replica en otras IES, apoyado en el relato de que una universidad regional ya puede tener canal propio sin copiar un campus de gran escala [20].",
    )

    h2(doc, "H. Pagos y compra")
    body(
        doc,
        "El medio de pago debe coincidir con el cliente. La institución paga por transferencia bancaria, PSE o factura electrónica, con orden de compra y soporte contable. No se le pide a una vicerrectoría que pague con Nequi. El estudiante, en cambio, no paga en esta etapa: su cuenta va incluida en la licencia. Si más adelante existiera un plan individual para un semillero o un programa que contrate por su cuenta, se habilitarían PSE, transferencia y, de forma opcional, Nequi o Daviplata para montos menores [21].",
    )
    body(
        doc,
        "El recorrido de compra institucional es el siguiente: solicitud de demo, reunión, propuesta económica, visto bueno jurídico y de datos personales, emisión de factura, pago y activación de la licencia. El contrato deja explícitos el tratamiento de datos (Ley 1581 de 2012), la vigencia anual, el soporte y el hecho de que el código fuente del prototipo académico no se cede, salvo acuerdo distinto [12], [21]. El comprobante y el acceso se entregan por correo. No hay envío físico.",
    )

    add_table(
        doc,
        ["Etapa", "Actor", "Acción", "Medio"],
        [
            ["Descubrimiento", "Estudiante o docente", "Ve el contenido y entra a la landing", "Instagram, TikTok, WhatsApp"],
            ["Prueba", "Usuario", "Crea cuenta y usa módulos", "Web Konex"],
            ["Interés institucional", "Directivo", "Pide demo", "Correo, LinkedIn, formulario"],
            ["Negociación", "Equipo Konex e institución", "Propuesta y revisión legal", "Reunión y documentos"],
            ["Pago", "Institución", "Transfiere o paga por PSE", "Banco, PSE, factura electrónica"],
            ["Activación", "Equipo Konex", "Habilita roles y dominio", "Correo y panel"],
            ["Uso y renovación", "Comunidad y directivo", "Usa el producto y decide continuar", "Plataforma e informe de indicadores"],
        ],
        "Tabla 12. Recorrido de compra y pago.",
    )

    h2(doc, "I. Indicadores")
    body(
        doc,
        "Lo que no se mide no se puede mejorar. Los indicadores se agrupan en marca, producto y negocio [18]. En marca se siguen alcance, impresiones, tasa de interacción y tráfico a la landing. En producto se siguen registros, usuarios activos semanales, grupos creados, recursos subidos, consultas al agente con respuesta correcta y solicitudes de horas sociales gestionadas. En negocio se siguen demos agendadas, propuestas enviadas, licencias cerradas, ingreso anual recurrente y costo de adquisición.",
    )

    add_table(
        doc,
        ["Indicador", "Meta del primer año", "Fuente de medición"],
        [
            ["Registros de prueba en UNIESPINAL", "300 cuentas", "Base de usuarios de Konex"],
            ["Usuarios activos semanales", "40 % de los registrados", "Sesiones del sistema"],
            ["Consultas correctas del agente", "≥ 80 % del banco de prueba", "Registro de consultas"],
            ["Demos institucionales", "8 reuniones", "Calendario comercial"],
            ["Licencias cerradas", "1 piloto + 1 campus, o 2 campus", "Contratos"],
            ["Costo de adquisición por licencia", "≤ 20 % del precio anual", "Pauta + horas de venta"],
            ["Interacción en Instagram", "≥ 4 % por pieza", "Estadísticas de Meta"],
        ],
        "Tabla 13. Indicadores de marketing y de negocio.",
    )

    body(
        doc,
        "Esos números no son vanidad. Si hay alcance y no hay registros, el contenido entretiene pero no convence. Si hay registros y no hay uso, el producto no retiene. Si hay uso y no hay licencia, el emprendimiento no se sostiene. El tablero se revisa cada mes, en paralelo con el calendario editorial [18], [19].",
    )

    # ------------------------------------------------------------------
    # CAPÍTULO 4
    # ------------------------------------------------------------------
    h1(doc, "VII. ANÁLISIS ECONÓMICO")
    h2(doc, "A. Costos")
    body(
        doc,
        "Los costos se separan en inversión de puesta en marcha, costos fijos de operación y costos variables por licencia. La puesta en marcha corresponde al prototipo ya construido: equipo de cómputo, pruebas del agente, conectividad, hosting temporal, papelería y trabajo de campo, por un millón ciento cincuenta mil pesos colombianos, sin cargar honorarios del equipo porque el desarrollo hace parte del trabajo de grado. Los costos fijos anuales son los que existen aunque se venda una sola licencia: hosting, dominio, mantenimiento, pauta y trámites. Los variables crecen con cada institución atendida: consumo del agente, almacenamiento y onboarding [22].",
    )

    add_table(
        doc,
        ["Costo fijo anual", "Valor (COP)"],
        [
            ["Hosting VPS, copias y certificado SSL", "960.000"],
            ["Dominio y correo transaccional", "120.000"],
            ["Mantenimiento y actualizaciones del equipo", "1.920.000"],
            ["Pauta y diseño de contenidos", "1.440.000"],
            ["Contabilidad, legal y habeas data", "480.000"],
            ["Herramientas de analítica y diseño", "240.000"],
            ["Total costos fijos anuales", "5.160.000"],
        ],
        "Tabla 14. Costos fijos anuales de operación.",
    )

    add_table(
        doc,
        ["Costo variable por licencia campus / año", "Valor (COP)"],
        [
            ["Consumo del agente de IA", "600.000"],
            ["Almacenamiento de recursos y evidencias", "240.000"],
            ["Onboarding y soporte de implantación", "360.000"],
            ["Total costo variable unitario", "1.200.000"],
        ],
        "Tabla 15. Costos variables por licencia institucional anual.",
    )

    h2(doc, "B. Costo unitario")
    body(
        doc,
        "La unidad de análisis es la licencia Plan Campus, de un año, que habilita a toda la comunidad de una institución. El costo unitario combina el costo variable con el prorrateo de los costos fijos, según (1), donde CU es el costo unitario, CF el costo fijo anual, Q el número de licencias y CV el costo variable por licencia [22].",
    )
    equation(doc, "CU = (CF / Q) + CV", 1)
    body(
        doc,
        "Con CF = 5.160.000 y CV = 1.200.000, el costo unitario depende de cuántas instituciones contraten. Si Q = 1, CU = 6.360.000. Si Q = 2, CU = 3.780.000. Si Q = 3, CU = 2.920.000. Esa caída explica por qué el emprendimiento no puede sostenerse con un único cliente a largo plazo: una sola licencia absorbe toda la estructura. Dos licencias ya diluyen el costo fijo por debajo del precio propuesto, como se verá en el punto de equilibrio.",
    )

    add_table(
        doc,
        ["Licencias (Q)", "CF / Q (COP)", "CV (COP)", "Costo unitario (COP)"],
        [
            ["1", "5.160.000", "1.200.000", "6.360.000"],
            ["2", "2.580.000", "1.200.000", "3.780.000"],
            ["3", "1.720.000", "1.200.000", "2.920.000"],
            ["4", "1.290.000", "1.200.000", "2.490.000"],
        ],
        "Tabla 16. Costo unitario de la licencia Plan Campus según el volumen.",
    )

    h2(doc, "C. Precio de venta")
    body(
        doc,
        "El precio se fija por valor y por capacidad de pago de una IES pequeña, no por imitar el arancel de un LMS mundial. El Plan Campus se propone en 4.800.000 COP al año. Ese monto cubre cuentas de la comunidad, módulos institucionales y agente, y equivale a 400.000 COP al mes: menos de lo que muchas instituciones gastan en un sitio web mal usado o en una pauta desordenada. El Plan Piloto, para una facultad y un año, se propone en 2.400.000 COP. El Plan Red, para varias sedes, en 8.400.000 COP. La unidad de los cálculos siguientes es el Plan Campus.",
    )
    body(
        doc,
        "La justificación es triple. Primera, el precio queda por encima del costo unitario a partir de dos clientes, con un margen de contribución de 3.600.000 COP por licencia (precio menos costo variable). Segunda, es accesible para una institución regional: no exige una licitación de cientos de millones. Tercera, comunica valor: no se cobra al estudiante y se le entrega a la universidad un canal propio, con roles y con protección de datos [12], [16], [17]. El precio se revisará cuando haya evidencia de uso y de costos reales de API y de soporte.",
    )

    add_table(
        doc,
        ["Plan", "Alcance", "Precio anual (COP)"],
        [
            ["Piloto", "Una facultad, módulos completos, un año", "2.400.000"],
            ["Campus", "Toda la IES, cuentas de la comunidad incluidas", "4.800.000"],
            ["Red", "Varias sedes o un convenio entre instituciones", "8.400.000"],
        ],
        "Tabla 17. Precio de venta propuesto.",
    )

    h2(doc, "D. Punto de equilibrio")
    body(
        doc,
        "El punto de equilibrio es la cantidad de licencias Campus con la que los ingresos igualan a los costos totales. Se calcula como se muestra en (2), donde PE es el punto de equilibrio en unidades, CF el costo fijo, PV el precio de venta y CV el costo variable unitario [22].",
    )
    equation(doc, "PE = CF / (PV - CV)", 2)
    body(
        doc,
        "Sustituyendo, PE = 5.160.000 / (4.800.000 - 1.200.000) = 5.160.000 / 3.600.000 = 1,43. Como no se venden fracciones de licencia, el punto de equilibrio operativo es de dos instituciones. Con una sola licencia Campus el emprendimiento aún pierde: el ingreso es 4.800.000 y el costo total es 6.360.000. Con dos licencias el ingreso es 9.600.000 y el costo total es 7.560.000, con un resultado positivo de 2.040.000 COP. Con tres licencias el resultado sube a 5.640.000 COP.",
    )

    add_table(
        doc,
        ["Q", "Ingresos (COP)", "Costo total (COP)", "Resultado (COP)"],
        [
            ["1", "4.800.000", "6.360.000", "-1.560.000"],
            ["2", "9.600.000", "7.560.000", "2.040.000"],
            ["3", "14.400.000", "8.760.000", "5.640.000"],
            ["4", "19.200.000", "9.960.000", "9.240.000"],
        ],
        "Tabla 18. Punto de equilibrio y resultado según el número de licencias Campus.",
    )

    body(
        doc,
        "El análisis tiene tres lecturas. La primera: el prototipo ya existe, de modo que la inversión hundida de 1.150.000 COP no entra en el cálculo de equilibrio operativo, aunque sí debe recuperarse con el margen de los primeros contratos. La segunda: UNIESPINAL, si contrata el Plan Campus o al menos el Plan Piloto, cubre el caso de uso y sirve de vitrina, pero no basta para equilibrar la operación. La tercera: la meta comercial del primer año (un piloto y un campus, o dos campus) está alineada con el punto de equilibrio y con los indicadores del Capítulo 3. El riesgo principal no es el costo variable; es no cerrar la segunda institución [16], [22].",
    )

    # ------------------------------------------------------------------
    # CIERRE
    # ------------------------------------------------------------------
    h1(doc, "VIII. CONCLUSIONES")
    body(
        doc,
        "Konex nació de un problema local y concreto: en UNIESPINAL la colaboración académica y la información institucional circulan, en la práctica, por chats que pierden archivos, mezclan lo oficial con lo informal y dejan fuera a quien no está en el grupo correcto. El emprendimiento responde con un producto web ya construido, de roles diferenciados, repositorio, grupos, eventos, horas sociales y un agente que consulta solo lo que la plataforma ya organizó.",
    )
    body(
        doc,
        "El trabajo permite tres conclusiones de producto. Primera, existe un hueco entre WhatsApp y el aula de cursos; ese hueco es el mercado. Segunda, el valor no está en tener más funciones, sino en persistir, clasificar y dejar que un agente institucional responda sin improvisar. Tercera, el estado actual es de prototipo funcional, no de implantación: el software demuestra la hipótesis, todavía no reemplaza los canales oficiales de la universidad [2], [4], [10].",
    )
    body(
        doc,
        "En marketing, el cliente que paga no es el estudiante. La comunicación debe ganar a Camila para que el producto se use y a Martha para que el producto se compre. Los canales cortos (Instagram, TikTok, correo, landing) y un calendario de cuatro semanas bastan para un equipo de dos personas, si cada pieza lleva a un solo llamado a la acción [17], [18], [19].",
    )
    body(
        doc,
        "En lo económico, el Plan Campus a 4.800.000 COP anuales cubre el servicio sin cobrarle al estudiante. El punto de equilibrio está en dos licencias. Un solo contrato no sostiene la estructura; dos la vuelven viable. Esa cifra convierte el discurso de emprendimiento en una meta verificable: cerrar, en el primer año de comercialización, al menos dos instituciones o un piloto más un campus [22].",
    )
    body(
        doc,
        "El aprendizaje principal del equipo es que un software de aula no es, por sí solo, un negocio, y que un negocio universitario no se construye contra la institución, sino con ella. Konex queda como marca, como prototipo y como oferta: conectar, colaborar y consultar, en un solo canal, a la comunidad que hoy se coordina en el chat.",
    )

    # ------------------------------------------------------------------
    # Referencias
    # ------------------------------------------------------------------
    h1(doc, "IX. REFERENCIAS")
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
        [
            ("A. Osterwalder y Y. Pigneur, ", False),
            ("Business Model Generation", True),
            (", Hoboken, NJ, USA: Wiley, 2010.", False),
        ],
        [
            ("P. Kotler y K. L. Keller, ", False),
            ("Dirección de marketing", True),
            (", 15.ª ed., México: Pearson, 2016.", False),
        ],
        [
            ("D. Chaffey y F. Ellis-Chadwick, ", False),
            ("Digital Marketing", True),
            (", 7th ed., Harlow, U.K.: Pearson, 2019.", False),
        ],
        [
            ("J. Pulizzi, ", False),
            ("Epic Content Marketing", True),
            (", New York, NY, USA: McGraw-Hill, 2014.", False),
        ],
        [
            ('Ministerio de Tecnologías de la Información y las Comunicaciones, "Política de Gobierno Digital," Bogotá, Colombia. [En línea]. Disponible: https://www.mintic.gov.co/. [Accedido: 05-nov-2026].', False),
        ],
        [
            ("Congreso de la República de Colombia, Ley 527 de 1999, por medio de la cual se define y reglamenta el acceso y uso de los mensajes de datos, del comercio electrónico y de las firmas digitales, ", False),
            ("Diario Oficial", True),
            (" No. 43.672, 21 ago. 1999.", False),
        ],
        [
            ("C. T. Horngren, S. M. Datar y M. V. Rajan, ", False),
            ("Cost Accounting: A Managerial Emphasis", True),
            (", 15th ed., Boston, MA, USA: Pearson, 2015.", False),
        ],
    ]
    for i, parts in enumerate(refs, start=1):
        ref_line(doc, i, parts)

    # ------------------------------------------------------------------
    # 31. Anexos
    # ------------------------------------------------------------------
    h1(doc, "X. ANEXOS")
    body(
        doc,
        "Los anexos reúnen material de apoyo que no cabe en el cuerpo sin interrumpir la lectura. Se numeran en el mismo orden en que se mencionan las evidencias del emprendimiento.",
    )

    h2(doc, "A. Identidad visual")
    body(
        doc,
        "Se reproduce el isotipo de Konex, ya presentado en la Figura 1, y la paleta de la Tabla 3. El granate institucional (#6E1F35) y el carbón (#0F172A) son los colores de marca en portada, redes y plataforma.",
    )
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 10, 6)
    try:
        run = p.add_run()
        run.add_picture(LOGO, width=Inches(1.8))
    except Exception:
        run = p.add_run("[Logotipo de Konex]")
        font(run, 12, italic=True)
    cap = doc.add_paragraph()
    cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(cap, 0, 10)
    run = cap.add_run("Figura 2. Isotipo de Konex (anexo de identidad).")
    font(run, 10)

    h2(doc, "B. Recorrido del cliente")
    body(
        doc,
        "El anexo sintetiza el recorrido descrito en la sección 23, desde el contenido digital hasta la renovación de la licencia.",
    )
    add_table(
        doc,
        ["Paso", "Qué ocurre"],
        [
            ["1", "El estudiante o el docente ve una pieza en Instagram, TikTok o WhatsApp."],
            ["2", "Entra a la landing y crea una cuenta de prueba."],
            ["3", "Usa grupos, recursos, eventos o el agente."],
            ["4", "Un directivo pide la demo de quince minutos."],
            ["5", "Se envía la propuesta de Plan Piloto o Plan Campus."],
            ["6", "La institución paga por transferencia, PSE o factura electrónica."],
            ["7", "Se activan roles y dominio. El indicador de uso alimenta la renovación."],
        ],
        "Tabla 19. Anexo. Recorrido del cliente institucional.",
    )

    h2(doc, "C. Proyección de punto de equilibrio")
    body(
        doc,
        "Se retoman los valores de (2) y de la Tabla 18. El equilibrio se alcanza en la segunda licencia Campus.",
    )
    add_table(
        doc,
        ["Concepto", "Valor"],
        [
            ["Costo fijo anual", "5.160.000 COP"],
            ["Costo variable unitario", "1.200.000 COP"],
            ["Precio Plan Campus", "4.800.000 COP"],
            ["Margen de contribución", "3.600.000 COP"],
            ["Punto de equilibrio", "1,43 → 2 licencias"],
            ["Resultado con 2 licencias", "2.040.000 COP"],
        ],
        "Tabla 20. Anexo. Síntesis del punto de equilibrio.",
    )

    h2(doc, "D. Presupuesto de puesta en marcha")
    body(
        doc,
        "El presupuesto corresponde a la inversión del prototipo, financiada por el equipo. UNIESPINAL no asume estos rubros. El software de desarrollo es libre.",
    )
    add_table(
        doc,
        ["Rubro", "Fuente", "Valor (COP)"],
        [
            ["Equipo de cómputo (ya adquirido)", "Equipo", "300.000"],
            ["Consumo del agente de IA (pruebas)", "Equipo", "350.000"],
            ["Internet y energía", "Equipo", "100.000"],
            ["Hosting y dominio temporal", "Equipo", "180.000"],
            ["Papelería e impresión", "Equipo", "100.000"],
            ["Transporte y trabajo de campo", "Equipo", "120.000"],
            ["Software de desarrollo (Laravel, MySQL, Bootstrap, VS Code)", "Libre", "0"],
            ["Recurso humano de los integrantes", "Aporte en especie", "0"],
            ["Total", "", "1.150.000"],
        ],
        "Tabla 21. Anexo. Presupuesto de puesta en marcha del prototipo.",
    )

    doc.save(OUT)
    print(OUT)


if __name__ == "__main__":
    build()
