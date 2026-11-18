<?php

/*
| Base de consulta del asistente de Konex.
| Textos tomados de las páginas públicas de uniespinal.edu.co consultadas
| el 17 de noviembre de 2026. No completa datos que el sitio no publica.
*/

return [
    'fichas' => [
        [
            'id' => 'identidad',
            'titulo' => 'Qué es UNIESPINAL',
            'peso' => 8,
            'terminos' => [
                'que es uniespinal',
                'que es la uniespinal',
                'que es la institucion',
                'hablame de uniespinal',
                'informacion de uniespinal',
                'informacion institucional',
                'cuentame de uniespinal',
                'sobre uniespinal',
                'historia',
                'itfip',
                'cambio de caracter',
                'cuando se creo',
                'cuando nacio',
                'presentacion institucional',
                'pei',
                'estatuto general',
            ],
            'respuesta' => <<<'TXT'
UNIESPINAL es la Institución Universitaria de El Espinal, entidad pública de educación superior. Su nombre anterior fue Instituto Tolimense de Formación Técnica Profesional (ITFIP), creado en 1980 para ampliar el acceso a la educación superior en el suroriente del Tolima. En 2026 se aprobó el cambio de carácter académico y nació la Institución Universitaria de El Espinal – UNIESPINAL.

Ofrece programas de pregrado por ciclos propedéuticos (técnico profesional, tecnología y profesional) y posgrados. Sus funciones son la docencia, la investigación, la proyección social y la internacionalización. El Proyecto Educativo Institucional vigente es el PEI 2026. El Estatuto General se adoptó con el Acuerdo 005 de 2026. El reglamento estudiantil que la institución sigue citando es el Acuerdo 6 de 2018.

NIT publicado: 800.173.719.0. Sede principal: calle 18 con carrera 1ª, barrio Arkabal, El Espinal, Tolima.
TXT,
            'fuente' => 'https://uniespinal.edu.co/presentacion-institucional/',
        ],
        [
            'id' => 'mision',
            'titulo' => 'Misión',
            'peso' => 12,
            'terminos' => ['mision', 'para que existe', 'proposito institucional'],
            'respuesta' => <<<'TXT'
Misión: la Institución Universitaria de El Espinal – UNIESPINAL es una entidad pública de educación superior del orden nacional. Ofrece programas de pregrado y posgrado en ciencia, tecnología, humanidades y artes. Forma profesionales íntegros, competentes para emprender, innovar y transformar el contexto regional, nacional e internacional, y aporta a la construcción del tejido social y a la sostenibilidad del medio ambiente. Integra docencia, investigación, proyección social e internacionalización, con sentido humanista y enfoque diferencial.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-1-mision-y-vision/',
        ],
        [
            'id' => 'vision',
            'titulo' => 'Visión',
            'peso' => 12,
            'terminos' => ['vision', 'al 2032', 'hacia donde va'],
            'respuesta' => <<<'TXT'
Visión al 2032: UNIESPINAL busca ser reconocida como institución líder en la formación de profesionales íntegros, innovadores, competentes y con sentido global, mediante programas pertinentes y en camino a la acreditación de alta calidad. Esa formación debe contribuir a transformar el entorno regional, nacional e internacional, con sostenibilidad, equidad, responsabilidad social, investigación e innovación.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-1-mision-y-vision/',
        ],
        [
            'id' => 'principios',
            'titulo' => 'Principios y valores',
            'peso' => 14,
            'terminos' => ['principio', 'principios', 'valores', 'valor institucional', 'axiolog'],
            'respuesta' => <<<'TXT'
Principios institucionales: integridad y buen gobierno; liderazgo e innovación transformadora; pertinencia social y equidad; ciudadanía global y convivencia diversa; sostenibilidad y compromiso ambiental.

Valores institucionales: honestidad, respeto, compromiso, diligencia, justicia, solidaridad y sentido de pertenencia.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-2-principios-y-valores-institucionales/',
        ],
        [
            'id' => 'oferta',
            'titulo' => 'Oferta académica',
            'peso' => 6,
            'terminos' => [
                'programas',
                'oferta academica',
                'que se estudia',
                'carreras',
                'facultad',
                'facultades',
                'ciclos propedeuticos',
                'tecnico profesional',
                'tecnologia en',
                'ingenieria de sistemas',
                'programacion web',
                'pregrado',
                'posgrado',
                'especializacion',
            ],
            'respuesta' => <<<'TXT'
La oferta es por ciclos propedéuticos: técnico profesional, tecnología y profesional, más especializaciones.

Facultad de Ingeniería y Ciencias Agroindustriales: Técnica Profesional en Programación Web, Mantenimiento Electrónico, Construcción de Edificaciones, Monitoreo Agrícola y Monitoreo Ambiental; tecnologías en Gestión Informática, Automatización Electrónica Industrial, Gestión de la Construcción, Producción de Agrocultivos y Gestión Ambiental; Ingenierías de Sistemas, Electrónica, Civil, Agronómica y Ambiental.

Facultad de Ciencias Económicas, Administrativas y Jurídicas: técnicos en Procesos Administrativos y Operaciones Contables; tecnologías en Gestión Empresarial y Gestión Contable y Financiera; Administración de Empresas y Contaduría Pública.

Facultad de Ciencias Sociales, Salud y Educación: técnicos en Operación de Servicios Comunitarios y Entrenamiento Deportivo; tecnologías en Gestión Comunitaria y Gestión Deportiva; Trabajo Social y Actividad Física y Deporte.

Especializaciones publicadas: Alta Gerencia, Gerencia de Proyectos de Construcción y Procesos de Intervención Social.

Códigos SNIES publicados en las páginas de cada programa. Si dos páginas oficiales no coinciden, se citan las dos:
- Técnica Profesional en Procesos Administrativos: 91430.
- Técnico Profesional en Operaciones Contables: 105381.
- Técnica Profesional en Programación Web: 109327.
- Técnica Profesional en Mantenimiento Electrónico: 101650.
- Técnica Profesional en Construcción de Edificaciones: 102043.
- Técnica Profesional en Monitoreo Agrícola: 106045 en el listado de técnicos y 23519 en la página de la facultad.
- Tecnología en Gestión Empresarial: 54204.
- Tecnología en Gestión Contable y Financiera: 117356. Resolución MEN 009904 del 12 de junio de 2024.
- Tecnología en Gestión Informática: 102121.
- Tecnología en Automatización Electrónica Industrial: 101649.
- Tecnología en Gestión de la Construcción: 102042.
- Tecnología en Producción de Agrocultivos: 106044.
- Administración de Empresas: 54198. 165 créditos. Resolución de renovación 006790 del 26 de abril de 2023.
- Contaduría Pública: 105383.
- Ingeniería de Sistemas: 102122. Resolución MEN 15862 del 18 de diciembre de 2019.
- Ingeniería Electrónica: 101648.
- Ingeniería Civil: 102039.
- Ingeniería Agronómica: 23521.
- Ingeniería Ambiental: 116856. Resolución 005625 del 25 de abril de 2024.
- Trabajo Social: 102626. Resolución MEN 003806 del 18 de marzo de 2022.
- Actividad Física y Deporte: 106116. Resolución 016423 del 24 de septiembre de 2024.

En las páginas consultadas no aparece el SNIES de Monitoreo Ambiental, Gestión Ambiental, Operación de Servicios Comunitarios, Entrenamiento Deportivo, Gestión Comunitaria, Gestión Deportiva ni de las tres especializaciones.
TXT,
            'fuente' => 'https://uniespinal.edu.co/oferta-academica-pregrados/',
        ],
        [
            'id' => 'codigos_snies',
            'titulo' => 'Códigos SNIES',
            'peso' => 36,
            'terminos' => [
                'snies',
                'codigo snies',
                'codigo del programa',
                'registro calificado',
                'resolucion men',
                '109327',
                '102122',
                '101650',
                '102043',
                '106045',
                '23519',
                '91430',
                '105381',
                '54204',
                '117356',
                '102121',
                '101649',
                '102042',
                '106044',
                '54198',
                '105383',
                '101648',
                '102039',
                '23521',
                '116856',
                '102626',
                '106116',
            ],
            'respuesta' => <<<'TXT'
Códigos SNIES publicados por UNIESPINAL. El código identifica el programa en el Sistema Nacional de Información de la Educación Superior. No reemplaza la resolución de registro calificado.

Facultad de Ingeniería y Ciencias Agroindustriales:
- Técnica Profesional en Programación Web: SNIES 109327.
- Técnica Profesional en Mantenimiento Electrónico: SNIES 101650.
- Técnica Profesional en Construcción de Edificaciones: SNIES 102043.
- Técnica Profesional en Monitoreo Agrícola: SNIES 106045 en https://itfip.uniespinal.edu.co/tecnicos-profesionales-ingenieria/ y SNIES 23519 en https://itfip.uniespinal.edu.co/facultad-ing-ciencias/. Hay que confirmar el vigente en el registro SNIES.
- Tecnología en Gestión Informática: SNIES 102121.
- Tecnología en Automatización Electrónica Industrial: SNIES 101649.
- Tecnología en Gestión de la Construcción: SNIES 102042.
- Tecnología en Producción de Agrocultivos: SNIES 106044.
- Ingeniería de Sistemas: SNIES 102122. Resolución MEN 15862 del 18 de diciembre de 2019.
- Ingeniería Electrónica: SNIES 101648.
- Ingeniería Civil: SNIES 102039.
- Ingeniería Agronómica: SNIES 23521.
- Ingeniería Ambiental: SNIES 116856. Resolución 005625 del 25 de abril de 2024. Coordinador publicado: Bruno Eliseo Ramírez Rengifo, bramirez@itfip.uniespinal.edu.co.

Facultad de Ciencias Económicas, Administrativas y Jurídicas:
- Técnica Profesional en Procesos Administrativos: SNIES 91430.
- Técnico Profesional en Operaciones Contables: SNIES 105381.
- Tecnología en Gestión Empresarial: SNIES 54204.
- Tecnología en Gestión Contable y Financiera: SNIES 117356. Resolución MEN 009904 del 12 de junio de 2024.
- Administración de Empresas: SNIES 54198. 165 créditos. Resolución de renovación del registro calificado 006790 del 26 de abril de 2023.
- Contaduría Pública: SNIES 105383.

Facultad de Ciencias Sociales, Salud y Educación:
- Trabajo Social: SNIES 102626. Resolución MEN 003806 del 18 de marzo de 2022.
- Profesional en Actividad Física y Deporte: SNIES 106116. Resolución 016423 del 24 de septiembre de 2024.

Las páginas consultadas no publican el SNIES de Técnica Profesional en Monitoreo Ambiental, Tecnología en Gestión Ambiental, Operación de Servicios Comunitarios, Entrenamiento Deportivo, Gestión Comunitaria, Gestión Deportiva, ni de las especializaciones en Alta Gerencia, Gerencia de Proyectos de Construcción y Procesos de Intervención Social.
TXT,
            'fuente' => 'https://uniespinal.edu.co/ingenieria-de-sistemas/',
        ],
        [
            'id' => 'sedes',
            'titulo' => 'Sedes y horario',
            'peso' => 10,
            'terminos' => [
                'donde queda',
                'direccion',
                'sede',
                'sedes',
                'ceres',
                'arkabal',
                'el espinal',
                'ibague',
                'venadillo',
                'chaparral',
                'horario de atencion',
                'el horario',
                'a que hora',
            ],
            'respuesta' => <<<'TXT'
Sede principal: calle 18 con carrera 1ª, barrio Arkabal, El Espinal, Tolima. Teléfono de atención al ciudadano: 316 625 4592. Horario: lunes a viernes, 8:00 a. m. a 12:00 p. m. y 2:00 p. m. a 6:00 p. m. Correo de contacto del sitio actual: info@uniespinal.edu.co. Algunas páginas antiguas todavía muestran info@itfip.edu.co.

Ampliación Ibagué: carrera 9ª N.º 35-40, barrio Gaitán, parte alta, Colegio Joaquín París. Contacto publicado: Marinela Ortiz Rodríguez, teléfono 312 595 4395, correo Mortiz84@itfip.edu.co. Horario: lunes a viernes, 2:00 p. m. a 6:00 p. m.

Ampliación Venadillo: calle 4 N.º 31-20, parque principal, Institución Educativa Técnica Camila Molano. Contacto: Rubén Darío Lamprea Huertas, rlamprea77@uniespinal.edu.co. El sitio no publica un teléfono completo de esta ampliación. Horario: lunes a viernes, 2:00 p. m. a 6:00 p. m.

Ampliación Chaparral: carrera 1 vía San Antonio, avenida de los estudiantes, Institución Educativa Técnica Soledad Medina. Contacto: Alberto Aranda Gutiérrez Medina, teléfono 301 221 4450. Horario: lunes a viernes, 2:00 p. m. a 6:00 p. m.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-5-sede-principal-y-ceres/',
        ],
        [
            'id' => 'contacto',
            'titulo' => 'Canales de atención',
            'peso' => 12,
            'terminos' => [
                'contacto',
                'telefono',
                'correo',
                'whatsapp',
                'pqrs',
                'queja',
                'reclamo',
                'atencion al ciudadano',
                'nit',
                'notificacion judicial',
            ],
            'respuesta' => <<<'TXT'
Canales publicados por UNIESPINAL:
- Atención al ciudadano y WhatsApp institucional de la FAQ: 316 625 4592.
- Línea fija principal: 608 661 1690.
- Línea anticorrupción: 01 8000 510 258.
- WhatsApp para estudiantes, enfocado en matrícula y encuestas: +57 311 291 1361.
- Correo de contacto: info@uniespinal.edu.co.
- Notificaciones judiciales: notificajudiciales@uniespinal.edu.co.
- Atención presencial: calle 18 con carrera 1ª, barrio Arkabal, El Espinal, Tolima, en el horario de lunes a viernes de 8:00 a. m. a 12:00 p. m. y de 2:00 p. m. a 6:00 p. m.
- PQRS, chat de ayuda en vivo y redes (Facebook, Instagram, TikTok, YouTube y X) desde el sitio web.

NIT: 800.173.719.0.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-11-preguntas-y-respuestas-frecuentes/',
        ],
        [
            'id' => 'directorio',
            'titulo' => 'Directorio',
            'peso' => 28,
            'terminos' => [
                'rector',
                'vicerrector',
                'vicerrectora',
                'decano',
                'decana',
                'coordinador',
                'coordinadora',
                'directorio',
                'registro y control',
            ],
            'respuesta' => <<<'TXT'
Directorio publicado en el sitio institucional:
- Rector: Mario Fernando Díaz Pava, mdiaz@uniespinal.edu.co.
- Vicerrectora académica: Tania Alejandra Moreno Durán, viceacademica@uniespinal.edu.co.
- Decano de la Facultad de Ingeniería y Ciencias Agroindustriales: Holman Reyes Puentes, hreyes@uniespinal.edu.co.
- Coordinador de Ingeniería de Sistemas: Víctor Alexis Aragón Briñez, varagon88@uniespinal.edu.co.
- Registro y Control Académico: Clelia Mina Medina, cmina@uniespinal.edu.co.
- Proyección social, internacionalización y convenios: Laura Marcela Reyes Vera, lreyes51@uniespinal.edu.co.

El listado completo de cargos está en el directorio institucional del sitio.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-7-directorio-institucional/',
        ],
        [
            'id' => 'calendario',
            'titulo' => 'Calendario académico',
            'peso' => 16,
            'terminos' => [
                'calendario',
                'calendario academico',
                'cuando inicia',
                'cuando empiezan',
                'cuando son las clases',
                'inscripcion',
                'inscripciones',
                'semestre a',
                'semestre b',
            ],
            'respuesta' => <<<'TXT'
Las fechas de clases, inscripción y matrícula no están copiadas en Konex: las fija el Consejo Académico en el calendario de cada periodo. UNIESPINAL tiene publicados, para descarga, el calendario del semestre A de 2026, el del semestre B de 2026 y el de especialización del periodo B de 2026. Hay que usar el PDF del periodo que corresponda; una fecha dicha de memoria puede estar vencida.
TXT,
            'fuente' => 'https://itfip.uniespinal.edu.co/calendario-academico-2/',
        ],
        [
            'id' => 'matricula_valores',
            'titulo' => 'Valores de matrícula',
            'peso' => 24,
            'terminos' => [
                'precio',
                'costo',
                'cuesta',
                'cuanto vale',
                'cuanto es',
                'tarifa',
                'tarifas',
                'derechos pecuniarios',
                'valor de la matricula',
                'valor de matricula',
                'valores de matricula',
            ],
            'respuesta' => <<<'TXT'
UNIESPINAL publica dos tablas de matrícula que no coinciden. Konex no liquida el pago ni aplica descuentos: el valor que se cobra es el que confirme Registro y Control Académico según el acuerdo de derechos pecuniarios vigente. El Acuerdo 049 de 2025 fija los derechos pecuniarios de la vigencia 2026.

Tabla “Valores de matrículas” (la especialización está rotulada 2026):
- Técnicos de Economía y de Ciencias Sociales: $1.291.742. Tecnologías de esas facultades: $1.550.090. Profesionales (Administración de Empresas, Contaduría Pública, Trabajo Social y Actividad Física y Deporte): $1.937.875.
- Técnicos de Ingeniería, incluida Programación Web: $1.421.176. Tecnologías de Ingeniería, incluida Gestión Informática: $1.937.875. Ingenierías (Sistemas, Electrónica, Civil, Agronómica y Ambiental): $2.325.134.
- Especialización en Alta Gerencia: matrícula $5.060.423, inscripción $129.437, derechos complementarios $103.235.

Tabla “Tarifas y reglamentos” (https://uniespinal.edu.co/tarifas-y-reglamentos/), presentada como tarifas vigentes:
- Inscripción: $137.514.
- Técnicos de Economía y de Ciencias Sociales: $1.371.442. Tecnologías: $1.645.731. Profesionales: $2.057.442.
- Técnicos de Ingeniería, salvo Programación Web: $1.509.857. En esa misma tabla, Programación Web aparece en $2.057.442 y Gestión Informática en $1.937.875. Ingenierías: $2.468.595.
- Especializaciones: matrícula $5.376.193, inscripción $137.514, derechos complementarios $109.677.

Antes de pagar, hay que contrastar el programa en las dos páginas o en la liquidación de Registro y Control. Los estímulos pueden cambiar el valor a cargo del estudiante.
TXT,
            'fuente' => 'https://uniespinal.edu.co/valores-matriculas/',
        ],
        [
            'id' => 'matricula_proceso',
            'titulo' => 'Proceso de matrícula',
            'peso' => 18,
            'terminos' => [
                'como me matriculo',
                'como matricularme',
                'requisitos de matricula',
                'requisitos para la matricula',
                'renovar la matricula',
                'renovacion de la matricula',
                'renovacion de matricula',
                'prematricula',
                'seguro estudiantil',
                'primera vez',
            ],
            'respuesta' => <<<'TXT'
La matrícula es el acto ante Admisiones, Registro y Control Académico, dentro de las fechas del calendario. Con ella la persona queda como estudiante regular del periodo y adquiere los derechos y deberes del reglamento.

Para matricularse por primera vez, el sitio de matrícula exige: haber sido admitido, haber hecho la prematrícula, el comprobante de pago de derechos pecuniarios y complementarios, la solicitud de estímulo, una póliza de seguro estudiantil contra accidentes de una compañía vigilada y la matrícula individual.

Para renovar: la misma póliza, el registro del pago, haber inscrito los espacios académicos del plan de estudios en las fechas previstas, la autorización de reintegro cuando aplique y la renovación del carné.
TXT,
            'fuente' => 'https://itfip.uniespinal.edu.co/matricula/',
        ],
        [
            'id' => 'estimulos',
            'titulo' => 'Estímulos y becas',
            'peso' => 20,
            'terminos' => [
                'beca',
                'becas',
                'estimulo',
                'estimulos',
                'descuento',
                'descuentos',
                'gratuidad',
                'matricula cero',
            ],
            'respuesta' => <<<'TXT'
La FAQ institucional enumera estos motivos de descuento o beca: rendimiento académico; escasos recursos económicos; población vulnerable; comunidades indígenas; convenios institucionales; situación de discapacidad; participación destacada en actividades académicas, culturales, deportivas, artísticas, sociales o comunitarias; representación estudiantil o de egresados en los consejos Directivo, Académico o de Facultad; y galardones en la Jornada Cultural y en eventos institucionales.

El Acuerdo 50 de noviembre de 2025 fija los criterios de estímulos educativos de pregrado y posgrado para los periodos A y B de 2026. Bienestar también orienta sobre política de gratuidad, Matrícula Cero de la Gobernación del Tolima y el programa Renta Joven. El porcentaje concreto depende de la convocatoria y de la aprobación del estímulo; Konex no lo calcula.
TXT,
            'fuente' => 'https://uniespinal.edu.co/1-11-preguntas-y-respuestas-frecuentes/',
        ],
        [
            'id' => 'grado',
            'titulo' => 'Derechos de grado',
            'peso' => 22,
            'terminos' => [
                'derechos de grado',
                'costo del grado',
                'para graduarme',
                'proceso de grado',
                'documentos para el grado',
                'documentos de grado',
            ],
            'respuesta' => <<<'TXT'
Para grado, el sitio pide estar a paz y salvo, fotocopia de la cédula ampliada al 150 %, fotocopia del certificado de las pruebas Saber Pro, dos fotocopias del formato de egresados y una foto. Esa documentación se entrega en Registro y Control Académico.

Derechos de grado publicados: colectivo técnico profesional $219.571 y por ventanilla $258.349; tecnológico, colectivo y ventanilla, $310.228; profesional, colectivo y ventanilla, $387.785; especialización colectiva $387.785.
TXT,
            'fuente' => 'https://uniespinal.edu.co/grados/',
        ],
        [
            'id' => 'bienestar',
            'titulo' => 'Bienestar universitario',
            'peso' => 14,
            'terminos' => [
                'bienestar',
                'psicologia',
                'enfermeria',
                'odontologia',
                'cultura',
                'deporte',
                'deportes',
                'permanencia',
                'alimentacion subsidiada',
            ],
            'respuesta' => <<<'TXT'
Bienestar Universitario cubre a estudiantes, docentes y personal administrativo. Áreas publicadas: salud (medicina general, enfermería, promoción y prevención, primeros auxilios y odontología de detartraje, profilaxis y operatoria); desarrollo humano (acompañamiento a la permanencia y graduación, con inclusión, pedagogía, psicología y trabajo social, además de orientación espiritual); promoción socioeconómica (estímulos, gratuidad, Matrícula Cero, alimentación subsidiada y orientación de Renta Joven); cultura (música, danza folclórica y fotografía); recreación y deportes (electivas, selecciones, torneos, juegos ASCUN y préstamo de escenarios).
TXT,
            'fuente' => 'https://uniespinal.edu.co/bienestar/',
        ],
        [
            'id' => 'konex',
            'titulo' => 'Konex en UNIESPINAL',
            'peso' => 20,
            'terminos' => [
                'que es konex',
                'para que sirve konex',
                'que hace konex',
                'konex',
            ],
            'respuesta' => <<<'TXT'
Konex es la plataforma web colaborativa de la comunidad de UNIESPINAL. Sirve para compartir recursos académicos, organizar grupos de estudio, consultar eventos y noticias, radicar horas sociales y preguntar datos institucionales. No reemplaza el aula virtual de los cursos ni los canales oficiales de matrícula: concentra la vida académica alrededor de la clase.

El estudiante consulta, comparte y solicita. El docente crea grupos de estudio. El directivo publica eventos y noticias y revisa las horas sociales. El correo de acceso es el institucional, con dominio uniespinal.edu.co.
TXT,
            'fuente' => null,
        ],
    ],
];
