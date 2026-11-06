# -*- coding: utf-8 -*-
"""Tarea IEEE: justificación numerada, objetivos, 40 antecedentes y marcos."""

from docx import Document
from docx.enum.text import WD_ALIGN_PARAGRAPH, WD_LINE_SPACING
from docx.oxml import OxmlElement
from docx.oxml.ns import qn
from docx.shared import Inches, Mm, Pt, RGBColor

OUT = r"C:\Users\nico1\Downloads\Konex-Tarea-IEEE-portada.docx"


def font(run, size=12, bold=False, italic=False):
    run.font.name = "Times New Roman"
    run._element.rPr.rFonts.set(qn("w:eastAsia"), "Times New Roman")
    run.font.size = Pt(size)
    run.bold = bold
    run.italic = italic
    run.font.color.rgb = RGBColor(0, 0, 0)


def space(p, before=0, after=8, first=0):
    p.paragraph_format.space_before = Pt(before)
    p.paragraph_format.space_after = Pt(after)
    p.paragraph_format.line_spacing_rule = WD_LINE_SPACING.SINGLE
    p.paragraph_format.first_line_indent = Inches(first)


def cover_line(doc, text, size=12, bold=False, before=0, after=6):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, before, after)
    if text:
        run = p.add_run(text)
        font(run, size, bold=bold)


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


def body(doc, text, first=0.5):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
    space(p, 0, 8, first)
    run = p.add_run(text)
    font(run)


def title(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 12, 12)
    run = p.add_run(text)
    font(run, 12, bold=True)


def center(doc, text, italic=False, after=4):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    space(p, 0, after)
    run = p.add_run(text)
    font(run, 12, italic=italic)


def h1(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 0, 12)
    run = p.add_run(text)
    font(run, 12, bold=True)


def h2(doc, text):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    space(p, 12, 0)
    run = p.add_run(text)
    font(run, 12, italic=True)


def ref_line(doc, number, parts):
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p.paragraph_format.left_indent = Inches(0.35)
    p.paragraph_format.first_line_indent = Inches(-0.35)
    space(p, 0, 8)
    first = True
    for text, italic in parts:
        prefix = f"[{number}] " if first else ""
        run = p.add_run(prefix + text)
        font(run, 11, italic=italic)
        first = False


def build():
    doc = Document()
    section = doc.sections[0]
    section.page_width = Mm(210)
    section.page_height = Mm(297)
    section.top_margin = Inches(1)
    section.bottom_margin = Inches(1)
    section.left_margin = Inches(1)
    section.right_margin = Inches(1)

    cover_line(
        doc,
        "Desarrollo de una plataforma web colaborativa para estudiantes, docentes y directivos en UNIESPINAL",
        16,
        True,
        48,
        18,
    )
    cover_line(doc, "Ingeniería de sistemas", 14, False, 6, 6)
    cover_line(doc, "", before=84, after=0)
    cover_line(doc, "Nicolás Esteban Martínez González", 14, False, 0, 4)
    cover_line(doc, "Damián Jesús Barbosa Álvarez", 14, False, 0, 84)
    cover_line(doc, "UNIESPINAL", 14, True, 0, 8)
    cover_line(doc, "Facultad de Ingeniería y Ciencias Agroindustriales")
    cover_line(doc, "Técnico profesional en programación web")
    cover_line(doc, "Asesores: Álvaro José Feria Rodríguez")
    cover_line(doc, "Jeniffer Julieth Palencia Quezada")
    cover_line(doc, "Octubre de 2026")

    body_section = doc.add_section()
    body_section.page_width = Mm(210)
    body_section.page_height = Mm(297)
    body_section.top_margin = Inches(1)
    body_section.bottom_margin = Inches(1)
    body_section.left_margin = Inches(1)
    body_section.right_margin = Inches(1)
    section.header.is_linked_to_previous = False
    section.header.paragraphs[0].clear()
    body_section.header.is_linked_to_previous = False
    add_page_number(body_section)
    set_page_start(body_section, 1)

    h1(doc, "1. Justificación")
    body(
        doc,
        "La justificación de Konex es social, práctica y teórica. En lo social, la colaboración desigual afecta el rendimiento y la permanencia. Quien no está en el chat correcto se entera tarde de una convocatoria, de un grupo o de un trámite de horas sociales. Una plataforma institucional reduce esa asimetría porque convierte la información en un hecho consultable, no en un rumor de grupo [1], [5]. En lo práctico, el proyecto entrega un software usable desde el navegador, sin exigir una aplicación nativa, y concentra en un solo entorno lo que hoy está repartido en varias aplicaciones.",
    )
    body(
        doc,
        "Konex centraliza, en un entorno seguro, la comunicación institucional, el intercambio de recursos académicos, la organización de grupos de estudio, la publicación de eventos y noticias, y la gestión de solicitudes de horas sociales en UNIESPINAL. No está pensada únicamente para el estudiante: reúne a estudiantes, profesores y directivos. Se plantea como producto mínimo viable de dieciséis semanas. El alcance es el desarrollo y la validación del prototipo, no la implementación en producción.",
    )
    body(
        doc,
        "En lo técnico, Laravel permite autenticación, sesiones, roles y protecciones frente a CSRF e inyección SQL. MySQL organiza apuntes, publicaciones, eventos y solicitudes en catálogos por asignatura y por rol. Bootstrap y JavaScript dan una interfaz responsiva, adecuada al celular con el que la comunidad ya estudia. A diferencia de las redes comerciales, Konex no se diseña para retener al usuario con algoritmos de entretenimiento, sino para la productividad académica [6], [7]. Un agente de consulta institucional, alimentado por datos ya centralizados, evita buscar entre mensajes o esperar a que alguien responda [8], [9].",
    )
    body(
        doc,
        "En lo teórico, el trabajo se sostiene en el aprendizaje colaborativo, en los entornos virtuales de aprendizaje y en la evidencia de que los chatbots institucionales acortan tiempos de atención cuando se alimentan de datos oficiales [5], [8], [9]. El aula virtual de cursos no reemplaza un espacio de comunidad. WhatsApp construye grupo, pero no es un ambiente virtual de aprendizaje [1]. El vacío que Konex ataca es local: UNIESPINAL tiene aula para cursos, pero no un espacio colaborativo que una a estudiantes, docentes y directivos alrededor de recursos, grupos y trámites.",
    )
    body(
        doc,
        "También hay una justificación normativa. Al autenticar usuarios con correo institucional y al guardar publicaciones, solicitudes y consultas, el sistema trata datos personales. La Ley 1581 de 2012 obliga a recoger solo lo necesario, a informar la finalidad y a proteger la información [10]. Ese deber orienta el diseño de la autenticación, de la recuperación de acceso y del agente de consulta, que no debe guardar historiales invasivos ni rastros ajenos a su función.",
    )

    h1(doc, "2. Objetivos")
    h2(doc, "2.1. Objetivo general")
    body(
        doc,
        "Desarrollar una plataforma web colaborativa para el intercambio de recursos académicos, la gestión de grupos de estudio y la circulación de información institucional entre estudiantes, docentes y directivos de UNIESPINAL.",
    )

    h2(doc, "2.2. Objetivos específicos")
    body(
        doc,
        "2.2.1. Analizar la arquitectura del software mediante Laravel, MySQL, Bootstrap y JavaScript, de modo que la interfaz sea dinámica, intuitiva y adaptable a dispositivos móviles.",
    )
    body(
        doc,
        "2.2.2. Diseñar un espacio centralizado que permita crear publicaciones, clasificar y compartir apuntes digitales por asignatura, y organizar grupos de estudio de forma ágil.",
    )
    body(
        doc,
        "2.2.3. Desarrollar un módulo de comunicación e interacción que facilite la difusión de eventos académicos, talleres y oportunidades formativas en un entorno seguro y enfocado en la productividad.",
    )
    body(
        doc,
        "2.2.4. Implementar autenticación por roles, recuperación de acceso y un agente de consulta institucional, con tratamiento de datos conforme a la Ley 1581 de 2012.",
    )

    h1(doc, "3. Antecedentes")
    body(
        doc,
        "Se presentan cuarenta antecedentes situados en los últimos seis años (2020-2026): veinte internacionales, cinco departamentales y quince nacionales. Cada uno se numera para su conteo. El criterio fue la cercanía con plataformas colaborativas, entornos virtuales, mensajería informal, chatbots institucionales o gestión digital de la educación superior.",
    )

    h2(doc, "3.1. Antecedentes internacionales")
    intl = [
        "3.1.1. Hodges et al. distinguieron la enseñanza remota de emergencia de un diseño en línea planificado. UNIESPINAL no necesita otro chat de crisis, sino un canal pensado para quedarse [3].",
        "3.1.2. Dhawan sintetizó ventajas y barreras del aprendizaje en línea y advirtió que la tecnología sola no garantiza equidad ni orden [4].",
        "3.1.3. Rapanta et al. mostraron que el profesorado universitario tuvo que reaprender a diseñar la mediación, no solo a encender una videollamada [11].",
        "3.1.4. Bond et al. mapearon la investigación de esa emergencia y confirmaron la saturación de herramientas y la fatiga digital [12].",
        "3.1.5. Adedoyin y Soykan subrayaron la brecha de conectividad y de competencias. Konex atiende ese límite al ser liviano y usable en el navegador del celular [13].",
        "3.1.6. Turnbull, Chugh y Luck revisaron décadas de investigación sobre LMS y mostraron que Moodle, Canvas o Blackboard organizan el curso, pero no siempre la vida informal del estudiante [6].",
        "3.1.7. Al-Maroof et al. estudiaron la adopción de Google Meet en pandemia y hallaron que la facilidad percibida impulsa el uso. Konex toma la lección de no cargar la interfaz [7].",
        "3.1.8. Smutny y Schreiberova revisaron chatbots educativos en Facebook Messenger y advirtieron la dependencia de una red comercial [14].",
        "3.1.9. Pérez, Daradoumis y Marquès redescubrieron el chatbot en educación y pidieron evaluaciones más rigurosas [8].",
        "3.1.10. Okonkwo y Ade-Ibijola sistematizaron aplicaciones de chatbots en apoyo administrativo, tutoría y evaluación [9].",
        "3.1.11. Labadze, Grigolia y Machaidze revisaron sesenta y siete estudios: el estudiante usa el chatbot para tareas y personalización; el docente gana tiempo; hay riesgos de exactitud y ética [15].",
        "3.1.12. Crompton y Burke describieron el estado de la inteligencia artificial en educación superior: personalización, soporte y automatización, con vacíos de privacidad [16].",
        "3.1.13. Kasneci et al. discutieron oportunidades y riesgos de los modelos de lenguaje en el aula y pidieron supervisión humana [17].",
        "3.1.14. La UNESCO publicó una guía para la IA generativa en educación que insiste en la centralidad de la persona, la transparencia y la protección de datos [18].",
        "3.1.15. La OCDE, en su panorama de educación digital, documentó que la gobernanza de los datos es tan crítica como la plataforma [19].",
        "3.1.16. Fiock actualizó la Comunidad de Indagación: un entorno digital necesita presencia social, cognitiva y docente; un repositorio sin diálogo no colabora [20].",
        "3.1.17. Martin, Sun y Westine revisaron dos décadas de investigación en línea y hallaron menos énfasis en la comunidad extra-aula, justo el hueco de Konex [21].",
        "3.1.18. Zhang, Zou y Cheng revisaron el aprendizaje asistido por chatbot y pidieron más evidencia de colaboración real, no solo de respuestas automáticas [22].",
        "3.1.19. Chiu et al. analizaron la IA en educación y advirtieron el riesgo de copiar sin comprender [23].",
        "3.1.20. Tlili et al. estudiaron ChatGPT como caso y concluyeron que las instituciones necesitan políticas propias, no improvisación [24].",
    ]
    for item in intl:
        body(doc, item)

    h2(doc, "3.2. Antecedentes departamentales")
    dept = [
        "3.2.1. La Universidad del Tolima opera el Campus Virtual para pregrados y posgrados a distancia. Es el referente departamental de mayor cobertura, pensado para programas virtuales y no para la colaboración cotidiana por asignatura y trámite [36].",
        "3.2.2. El IDEAD de la Universidad del Tolima mantiene Tu Aula Virtual, con recuperación de clave y soporte propio. Confirma cultura de aula en línea en Ibagué, anclada al curso y no a la comunidad [37].",
        "3.2.3. UNIESPINAL, antes ITFIP, dispone de Aula Virtual sobre Moodle para cursos y cátedra institucional. Digitaliza la formación y, a la vez, deja visible el hueco: no hay grupos de estudio horizontales ni gestión de horas sociales [38].",
        "3.2.4. La Universidad de Ibagué articula servicios digitales de ventanilla y comunicación institucional desde la sede Ambalá. Es un antecedente de ciudad sobre canales oficiales, aunque no publica un producto equivalente a Konex [39].",
        "3.2.5. En 2020, el encuentro de semilleros del entonces ITFIP se hizo por Google Meet, sala por programa. Esa improvisación de pandemia es la práctica que este proyecto quiere superar con un canal propio y ordenado [40].",
    ]
    for item in dept:
        body(doc, item)

    h2(doc, "3.3. Antecedentes nacionales")
    nac = [
        "3.3.1. Espinel, Hernández y Prada compararon WhatsApp y el campus virtual con 606 estudiantes de Comunicación Social. WhatsApp construye comunidad, pero no es un ambiente virtual de aprendizaje [1].",
        "3.3.2. Echeverri y Manjarrés implementaron un asistente académico con procesamiento de lenguaje natural en el Politécnico Colombiano Jaime Isaza Cadavid y mejoraron tiempos de atención en trámites [2].",
        "3.3.3. García-Chitiva revisó el aprendizaje colaborativo mediado por internet en educación superior y lo situó como construcción social, no como suma de archivos [5].",
        "3.3.4. Veytia y Bastidas analizaron WhatsApp como recurso de trabajo grupal universitario y confirmaron su utilidad táctica y su desorden [25].",
        "3.3.5. Guiñez-Cabrera y Mansilla estudiaron WhatsApp Web con fines académicos en pandemia y hallaron inmediatez junto con sobrecarga [26].",
        "3.3.6. Villamizar y Viviescas, en la Universidad Autónoma de Bucaramanga, desarrollaron una plataforma web colaborativa con foros, recursos y un asistente de IA. El contexto no es el Tolima ni incluye horas sociales ni roles de directivos [27].",
        "3.3.7. Mejia-Lobo, Gil-Fernández y Calderón-Garrido evaluaron Ingeniemos.net en cuatro universidades de Manizales, con 280 estudiantes de Ingeniería de Sistemas. La interacción incrementó el aprendizaje, pero el objeto era una carrera, no toda la comunidad [28].",
        "3.3.8. Manosalva y Criollo revisaron experiencias colombianas de plataformas colaborativas en educación pública y propusieron un Ecosistema Colaborativo Educativo; su escala es de política, no de un MVP institucional [29].",
        "3.3.9. Wiesner y Burgoa documentaron aprendizaje colaborativo internacional entre instituciones de Colombia y Chile, útil para pensar la colaboración más allá del aula local [30].",
        "3.3.10. El Ministerio de Educación Nacional sostiene portales como Colombia Aprende, que centralizan recursos públicos, pero no resuelven la vida cotidiana de un campus pequeño [31].",
        "3.3.11. MinTIC ha impulsado gobierno digital y servicios en línea. Ese marco legitima digitalizar trámites; no sustituye el diseño pedagógico de Konex [32].",
        "3.3.12. La Universidad Nacional de Colombia opera campus y servicios virtuales de gran escala. Su lección es la necesidad de roles y de soporte, no copiar esa complejidad [33].",
        "3.3.13. El SENA, con SOFIA Plus, demuestra que un país puede concentrar matrícula y trámites de formación en un solo sistema, con otra gobernanza y otro usuario [34].",
        "3.3.14. La Superintendencia de Industria y Comercio ha orientado el cumplimiento del habeas data en aplicaciones digitales, refuerzo práctico de la Ley 1581 [35].",
        "3.3.15. Esos referentes nacionales muestran que WhatsApp no es campus, que un chatbot institucional ahorra ventanilla y que una red académica propia incrementa el aprendizaje. Falta, en una institución de ciudad intermedia, un desarrollo liviano de roles mixtos que trate el dato personal con el rigor de la Ley 1581. Ese es el lugar de Konex [1], [2], [10].",
    ]
    for item in nac:
        body(doc, item)

    h1(doc, "4. Marcos de referencia")
    h2(doc, "4.1. Marco administrativo")
    body(
        doc,
        "El proyecto se adelanta en la Institución Universitaria de El Espinal (UNIESPINAL), Facultad de Ingeniería y Ciencias Agroindustriales, programa de Técnico profesional en programación web, con acompañamiento de Ingeniería de Sistemas. Los estudiantes investigadores son Nicolás Esteban Martínez González, investigador principal y desarrollador, y Damián Jesús Barbosa Álvarez, desarrollador e investigador. Los asesores son Álvaro José Feria Rodríguez y Jeniffer Julieth Palencia Quezada.",
    )
    body(
        doc,
        "Konex se concibe como prototipo de aula y de laboratorio, no como sistema misional de la Rectoría. Los roles del software —estudiante, docente y directivo— copian la estructura real de la comunidad. El cronograma de dieciséis semanas organiza análisis, diseño, construcción, pruebas e informe. Los recursos son de los investigadores, con software libre y sin carga presupuestal institucional. El producto podrá registrarse como soporte lógico ante la Dirección Nacional de Derecho de Autor.",
    )

    h2(doc, "4.2. Marco teórico")
    body(
        doc,
        "El aprendizaje colaborativo sostiene que el conocimiento se construye en la interacción, no en el aislamiento [5], [20]. El CSCL y los entornos virtuales explican por qué un medio digital puede mediar la construcción conjunta si hay herramientas de comunicación, de compartición y de coordinación [21], [22]. La Comunidad de Indagación añade que sin presencia social y docente el entorno se vuelve repositorio muerto [20]. Por eso Konex incluye publicación, comentario y roles que emiten información oficial.",
    )
    body(
        doc,
        "Los estudios de LMS muestran que la utilidad percibida y la facilidad de uso predicen la adopción [6], [7]. El patrón Modelo-Vista-Controlador, materializado en Laravel, separa datos, interfaz y control. La investigación aplicada-tecnológica, según Ortiz Uribe y García Nieto, justifica probar la hipótesis en el prototipo: el criterio es la efectividad de lo construido [41]. La literatura de chatbots institucionales sostiene que un agente es útil cuando responde con datos de la organización [2], [9], [15].",
    )

    h2(doc, "4.3. Marco legal")
    body(
        doc,
        "La Constitución Política de Colombia, en su artículo 15, garantiza la intimidad, el buen nombre y el habeas data [42]. Ese derecho se desarrolla en la Ley 1581 de 2012, régimen general de protección de datos personales, norma axial de este proyecto [10].",
    )
    body(
        doc,
        "La Ley 1581 de 2012 aplica porque Konex autentica usuarios con correo institucional y almacena nombre, rol, publicaciones, solicitudes de horas sociales y, si se habilita, consultas al agente. En la etapa de prototipo se tratan datos de prueba o de participantes que hayan consentido. Los principios que se implementan son legalidad, finalidad, libertad, veracidad, transparencia, acceso restringido, seguridad y confidencialidad. El principio de minimización se traduce en no pedir datos ajenos al funcionamiento, no guardar historial invasivo y no emplear algoritmos de retención comercial. La recuperación de acceso se plantea para que el usuario no pierda la cuenta y, a la vez, para no exponer secretos en texto plano.",
    )
    body(
        doc,
        "El Decreto 1377 de 2013 reglamenta la Ley 1581 y precisa consentimiento, políticas de tratamiento y medidas de seguridad [43]. La Ley 1273 de 2009 tipifica los atentados contra la confidencialidad, integridad y disponibilidad de los datos, y obliga a cifrar contraseñas, validar formularios y restringir el acceso por rol [44]. La Ley 23 de 1982 y la Decisión 351 de la Comunidad Andina protegen el software como obra y recuerdan que los apuntes ajenos no se republican sin respeto al derecho de autor [45], [46]. La Ley 30 de 1992 organiza la educación superior y sitúa a la institución como responsable del servicio educativo; Konex no reemplaza el aula ni el reglamento estudiantil [47].",
    )

    h2(doc, "4.4. Marco conceptual")
    body(
        doc,
        "4.4.1. Aprendizaje colaborativo: proceso en el que el saber se construye con pares, profesores y demás miembros de la institución.",
    )
    body(
        doc,
        "4.4.2. Repositorio académico centralizado: almacén organizado de apuntes, guías, talleres, eventos, noticias y solicitudes, distinto del chat porque permite buscar y reutilizar.",
    )
    body(
        doc,
        "4.4.3. Arquitectura MVC: separación de modelo, vista y controlador. En Laravel evita mezclar la base de datos con la interfaz y con las reglas de cada rol.",
    )
    body(
        doc,
        "4.4.4. Producto mínimo viable: autenticación por roles, grupos, repositorio, eventos, horas sociales, recuperación de acceso y agente de consulta. Quedan fuera la app nativa y la puesta en producción.",
    )
    body(
        doc,
        "4.4.5. Control de acceso basado en roles: el estudiante colabora y solicita; el docente y el directivo pueden emitir información oficial.",
    )
    body(
        doc,
        "4.4.6. Agente de consulta institucional: interpreta una pregunta y responde con datos ya centralizados. No navega internet ni entretiene.",
    )
    body(
        doc,
        "4.4.7. Asimetría de la información: unos saben y otros no. Konex la reduce al publicar en un solo lugar.",
    )
    body(
        doc,
        "4.4.8. Dato personal y tratamiento: según la Ley 1581, dato personal es cualquier información vinculada a una persona determinada o determinable, y tratamiento es cualquier operación sobre ese dato [10]. En Konex, el correo institucional y el perfil son datos personales.",
    )

    h1(doc, "5. Referencias")
    refs = [
        [('G. A. Espinel Rubio, C. A. Hernández Suárez y R. Prada Núñez, "WhatsApp vs. campus virtual institucional en tiempos de covid-19. Percepción de los estudiantes de comunicación social," ', False), ("Rev. Boletín Redipe", True), (", vol. 10, no. 10, pp. 519-533, oct. 2021.", False)],
        [('M. M. Echeverri Torres y R. Manjarrés-Betancur, "Asistente virtual académico utilizando tecnologías cognitivas de procesamiento de lenguaje natural," ', False), ("Rev. Politécnica", True), (", vol. 16, no. 31, pp. 85-96, 2020.", False)],
        [('C. Hodges, S. Moore, B. Lockee, T. Trust y A. Bond, "The difference between emergency remote teaching and online learning," ', False), ("Educause Review", True), (", 27 mar. 2020. [En línea]. Disponible: https://er.educause.edu/", False)],
        [('S. Dhawan, "Online learning: A panacea in the time of COVID-19 crisis," ', False), ("J. Educ. Technol. Syst.", True), (", vol. 49, no. 1, pp. 5-22, 2020.", False)],
        [('M. del P. García-Chitiva, "Aprendizaje colaborativo, mediado por internet, en procesos de educación superior," ', False), ("Rev. Electrón. Educare", True), (", vol. 25, no. 2, pp. 1-19, 2021.", False)],
        [('D. Turnbull, R. Chugh y J. Luck, "Learning management systems: A review of the research published in the last 40 years," ', False), ("J. Comput. Educ.", True), (", 2021.", False)],
        [('R. S. Al-Maroof, S. A. Salloum, A. E. Hassanien y K. Shaalan, "Fear from COVID-19 and technology adoption: The impact of Google Meet during coronavirus pandemic," ', False), ("Interact. Learn. Environ.", True), (", 2020.", False)],
        [('J. Q. Pérez, T. Daradoumis y J. M. Marquès Puig, "Rediscovering the use of chatbots in education: A systematic literature review," ', False), ("Comput. Appl. Eng. Educ.", True), (", vol. 28, no. 6, pp. 1549-1565, 2020.", False)],
        [('C. W. Okonkwo y A. Ade-Ibijola, "Chatbots applications in education: A systematic review," ', False), ("Comput. Educ.: Artif. Intell.", True), (", vol. 2, p. 100033, 2021.", False)],
        [("Congreso de la República de Colombia, Ley 1581 de 2012, Por la cual se dictan disposiciones generales para la protección de datos personales, 17 oct. 2012, Diario Oficial No. 48.587. [En línea]. Disponible: http://www.secretariasenado.gov.co/", False)],
        [('C. Rapanta, L. Botturi, P. Goodyear, L. Guàrdia y M. Koole, "Online university teaching during and after the Covid-19 crisis: Refocusing teacher presence and learning activity," ', False), ("Postdigital Sci. Educ.", True), (", vol. 2, pp. 923-945, 2020.", False)],
        [('M. Bond, S. Bedenlier, V. I. Marín y M. Händel, "Emergency remote teaching in higher education: Mapping the first global online semester," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 18, art. 50, 2021.", False)],
        [('O. B. Adedoyin y E. Soykan, "Covid-19 pandemic and online learning: The challenges and opportunities," ', False), ("Interact. Learn. Environ.", True), (", 2020.", False)],
        [('P. Smutny y P. Schreiberova, "Chatbots for learning: A review of educational chatbots for the Facebook Messenger," ', False), ("Comput. Educ.", True), (", vol. 151, p. 103862, 2020.", False)],
        [('L. Labadze, M. Grigolia y L. Machaidze, "Role of AI chatbots in education: Systematic literature review," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 20, art. 56, 2023.", False)],
        [('H. Crompton y D. Burke, "Artificial intelligence in higher education: The state of the field," ', False), ("Int. J. Educ. Technol. High. Educ.", True), (", vol. 20, art. 22, 2023.", False)],
        [('E. Kasneci et al., "ChatGPT for good? On opportunities and challenges of large language models for education," ', False), ("Learn. Individ. Differ.", True), (", vol. 103, p. 102274, 2023.", False)],
        [("UNESCO, ", False), ("Guidance for generative AI in education and research", True), (". París, Francia: UNESCO, 2023.", False)],
        [("OECD, ", False), ("OECD Digital Education Outlook 2021: Pushing the Frontiers with Artificial Intelligence, Blockchain and Robots", True), (". París, Francia: OECD Publishing, 2021.", False)],
        [('H. Fiock, "Designing a community of inquiry in online courses," ', False), ("Int. Rev. Res. Open Distrib. Learn.", True), (", vol. 21, no. 1, pp. 135-153, 2020.", False)],
        [('F. Martin, T. Sun y C. D. Westine, "A systematic review of research on online teaching and learning from 2009 to 2018," ', False), ("Comput. Educ.", True), (", vol. 159, p. 104009, 2020.", False)],
        [('R. Zhang, D. Zou y G. Cheng, "A review of chatbot-assisted learning: Pedagogical approaches, implementations, factors leading to effectiveness, theories, and future directions," ', False), ("Interact. Learn. Environ.", True), (", vol. 32, no. 8, pp. 4529-4557, 2023.", False)],
        [('T. K. F. Chiu, Q. Xia, X. Zhou, C. S. Chai y M. Cheng, "Systematic literature review on opportunities, challenges, and future research recommendations of artificial intelligence in education," ', False), ("Comput. Educ.: Artif. Intell.", True), (", vol. 4, p. 100118, 2023.", False)],
        [('A. Tlili et al., "What if the devil is my guardian angel: ChatGPT as a case study of using chatbots in education," ', False), ("Smart Learn. Environ.", True), (", vol. 10, art. 15, 2023.", False)],
        [('M. G. Veytia Bucheli y F. A. Bastidas Terán, "WhatsApp como recurso para el trabajo grupal en estudiantes universitarios," ', False), ("Apertura", True), (", vol. 12, no. 2, pp. 74-93, 2020.", False)],
        [('N. A. Guiñez-Cabrera y K. Mansilla-Obando, "WhatsApp Web con fines académicos en tiempos de la covid-19," ', False), ("Apertura", True), (", vol. 13, no. 2, 2021.", False)],
        [('M. Villamizar Carvajal y P. A. Viviescas Jaimes, "Plataforma web colaborativa de aprendizaje académico," trabajo de grado, Universidad Autónoma de Bucaramanga, Bucaramanga, Colombia, 2024. [En línea]. Disponible: http://hdl.handle.net/20.500.12749/29817', False)],
        [('M. Mejia-Lobo, R. Gil-Fernández y D. Calderón-Garrido, "Evaluación del impacto de una red social académica diseñada para promover el aprendizaje entre estudiantes de Ingeniería de Sistemas en Manizales, Colombia," ', False), ("Edutec, Rev. Electrón. Tecnol. Educ.", True), (", no. 91, pp. 140-156, 2025.", False)],
        [('B. S. Manosalva Pedraza y M. Y. Criollo Guerrero, "Plataformas colaborativas como estrategia intersectorial en la educación pública," monografía, Universidad Nacional Abierta y a Distancia, Colombia, 2026. [En línea]. Disponible: https://repository.unad.edu.co/handle/10596/84233', False)],
        [('V. Wiesner y C. Burgoa, "Experiencia de un aprendizaje colaborativo internacional entre instituciones de Educación Superior de Colombia y Chile," ', False), ("Praxis & Saber", True), (", vol. 14, no. 37, 2023.", False)],
        [('Ministerio de Educación Nacional de Colombia. "Colombia Aprende." [En línea]. Disponible: https://www.colombiaaprende.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Ministerio de Tecnologías de la Información y las Comunicaciones de Colombia. "Gobierno Digital." [En línea]. Disponible: https://gobiernodigital.mintic.gov.co/. [Accedido: 4-oct-2026].', False)],
        [('Universidad Nacional de Colombia. "Portal institucional." [En línea]. Disponible: https://unal.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Servicio Nacional de Aprendizaje (SENA). "SOFIA Plus." [En línea]. Disponible: https://sofiaplus.sena.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Superintendencia de Industria y Comercio. "Protección de datos personales." [En línea]. Disponible: https://www.sic.gov.co/. [Accedido: 4-oct-2026].', False)],
        [('Universidad del Tolima. "Campus Virtual." [En línea]. Disponible: https://campusvirtual.ut.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Universidad del Tolima, IDEAD. "Tu Aula Virtual." [En línea]. Disponible: https://tuaulavirtual.ut.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Institución Universitaria de El Espinal (UNIESPINAL). "Aula Virtual UNIESPINAL." [En línea]. Disponible: https://aulavirtual.itfip.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Universidad de Ibagué. "Sitio institucional." [En línea]. Disponible: https://www.unibague.edu.co/. [Accedido: 4-oct-2026].', False)],
        [('Instituto Tolimense de Formación Técnica Profesional (ITFIP). "Programación de encuentro de semilleros 2020." Espinal, Colombia, 27 abr. 2020. [En línea]. Disponible: https://itfip.edu.co/programacion-de-encuentro-de-semilleros-2020/', False)],
        [("F. G. Ortiz Uribe y M. del P. García Nieto, ", False), ("Metodología de la investigación: el proceso y sus técnicas", True), (". México: Limusa, 2006.", False)],
        [("República de Colombia, Constitución Política de Colombia, art. 15, 1991.", False)],
        [("Presidencia de la República de Colombia, Decreto 1377 de 2013, Por el cual se reglamenta parcialmente la Ley 1581 de 2012, 27 jun. 2013, Diario Oficial No. 48.834.", False)],
        [("Congreso de la República de Colombia, Ley 1273 de 2009, Por medio de la cual se modifica el Código Penal y se crea un nuevo bien jurídico tutelado denominado de la protección de la información y de los datos, 5 ene. 2009, Diario Oficial No. 47.223.", False)],
        [("Congreso de la República de Colombia, Ley 23 de 1982, Sobre derechos de autor, 28 ene. 1982.", False)],
        [("Comisión del Acuerdo de Cartagena, Decisión 351 de 1993, Régimen común sobre derecho de autor y derechos conexos, 17 dic. 1993.", False)],
        [("Congreso de la República de Colombia, Ley 30 de 1992, Por la cual se organiza el servicio público de la educación superior, 28 dic. 1992, Diario Oficial No. 40.700.", False)],
    ]
    for i, parts in enumerate(refs, 1):
        ref_line(doc, i, parts)

    doc.save(OUT)
    print(OUT)


if __name__ == "__main__":
    build()
