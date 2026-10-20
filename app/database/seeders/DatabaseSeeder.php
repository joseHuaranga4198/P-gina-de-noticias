<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@noticias.local',
            'password' => Hash::make($adminPassword = Str::random(16)),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Save credentials to private file
        $credentialsPath = database_path('credenciales_demo.txt');
        file_put_contents($credentialsPath, "=== CREDENCIALES DE DEMOSTRACIÓN ===\n" .
            "Email: admin@noticias.local\n" .
            "Contraseña: {$adminPassword}\n" .
            "URL Admin: http://localhost:8000/admin\n" .
            "Generado: " . now()->format('Y-m-d H:i:s') . "\n");

        // Create categories with realistic Spanish news data
        $categories = [
            ['name' => 'Política', 'slug' => 'politica', 'description' => 'Noticias sobre política nacional e internacional', 'color' => '#DC2626'],
            ['name' => 'Economía', 'slug' => 'economia', 'description' => 'Información financiera y económica', 'color' => '#059669'],
            ['name' => 'Tecnología', 'slug' => 'tecnologia', 'description' => 'Avances tecnológicos e innovación', 'color' => '#2563EB'],
            ['name' => 'Deportes', 'slug' => 'deportes', 'description' => 'Cobertura deportiva completa', 'color' => '#D97706'],
            ['name' => 'Cultura', 'slug' => 'cultura', 'description' => 'Arte, música, cine y literatura', 'color' => '#7C3AED'],
            ['name' => 'Ciencia', 'slug' => 'ciencia', 'description' => 'Descubrimientos y avances científicos', 'color' => '#0891B2'],
            ['name' => 'Salud', 'slug' => 'salud', 'description' => 'Noticias de salud y bienestar', 'color' => '#E11D48'],
            ['name' => 'Educación', 'slug' => 'educacion', 'description' => 'Noticias del sector educativo', 'color' => '#4F46E5'],
        ];

        $createdCategories = [];
        foreach ($categories as $catData) {
            $createdCategories[] = Category::create($catData);
        }

        // Create realistic demo news articles in Spanish
        $newsData = [
            [
                'title' => 'Nuevo acuerdo internacional impulsa la transición energética en América Latina',
                'excerpt' => 'Líderes de 15 países latinoamericanos firmaron un acuerdo histórico para acelerar la adopción de energías renovables en la región.',
                'content' => '<p>En una cumbre celebrada en Ciudad de México, representantes de 15 naciones latinoamericanas alcanzaron un acuerdo sin precedentes para impulsar la transición hacia fuentes de energía limpia.</p><p>El pacto, denominado "Alianza Verde Latinoamericana", establece metas ambiciosas para reducir las emisiones de carbono en un 40% para 2035. Los países firmantes se comprometieron a destinar al menos el 5% de su presupuesto nacional a proyectos de energía solar, eólica e hidroeléctrica.</p><p>La presidenta de la cumbre destacó que "este acuerdo representa un paso fundamental hacia un futuro sostenible para nuestros pueblos". Los expertos internacionales han calificado esta iniciativa como una de las más ambiciosas en materia ambiental.</p><p>Entre las medidas concretas se incluyen la creación de un fondo regional de inversión verde, programas de capacitación técnica y la implementación de estándares comunes de eficiencia energética.</p>',
                'category_index' => 0, 'is_featured' => true,
            ],
            [
                'title' => 'Avance revolucionario en inteligencia artificial transforma el diagnóstico médico',
                'excerpt' => 'Un equipo de investigadores desarrolló un sistema de IA capaz de detectar enfermedades con una precisión superior al 98%.',
                'content' => '<p>Investigadores de una universidad latinoamericana presentaron un sistema de inteligencia artificial que promete revolucionar el diagnóstico médico temprano.</p><p>El sistema, basado en aprendizaje profundo, puede analizar imágenes médicas y datos clínicos para detectar más de 50 condiciones de salud con una precisión que supera el 98%, según los resultados publicados en una prestigiosa revista científica.</p><p>"Nuestra herramienta no pretende reemplazar a los médicos, sino potenciar su capacidad diagnóstica", explicó la investigadora principal del proyecto. "En pruebas clínicas, el sistema identificó condiciones que habían pasado desapercibidas en evaluaciones previas".</p><p>El desarrollo ha recibido financiamiento adicional para expandir sus capacidades y se espera que esté disponible en hospitales públicos dentro de los próximos dos años.</p>',
                'category_index' => 2, 'is_featured' => true,
            ],
            [
                'title' => 'Selección nacional se clasifica para la final del campeonato continental',
                'excerpt' => 'Con una victoria contundente, el equipo nacional aseguró su lugar en la gran final del torneo.',
                'content' => '<p>La selección nacional logró una victoria memorable al imponerse 3-1 en la semifinal del campeonato continental, asegurando su presencia en la gran final que se disputará el próximo mes.</p><p>Los goles fueron anotados en momentos clave del partido, demostrando la madurez táctica del equipo dirigido por el seleccionador nacional. El primer gol llegó en el minuto 23, seguido por un tanto espectacular en el 56 y el cierre definitivo en el 78.</p><p>Miles de aficionados celebraron en las principales ciudades del país tras el pitido final. La final se disputará ante el ganador de la otra semifinal en un estadio con capacidad para 80,000 espectadores.</p><p>"Este equipo ha demostrado carácter y determinación. Estamos listos para buscar el título", declaró el capitán del equipo en la conferencia de prensa posterior al encuentro.</p>',
                'category_index' => 3, 'is_featured' => true,
            ],
            [
                'title' => 'Descubren nueva especie marina en aguas del Pacífico sur',
                'excerpt' => 'Biólogos marinos identificaron una especie de pez nunca antes catalogada durante una expedición científica.',
                'content' => '<p>Una expedición científica internacional ha descubierto una nueva especie de pez en las profundidades del Océano Pacífico sur, a más de 3,000 metros de profundidad.</p><p>El hallazgo fue realizado mediante un vehículo submarino no tripulado equipado con cámaras de alta definición y sensores especializados. La nueva especie presenta características bioluminiscentes únicas que la distinguen de todas las especies conocidas hasta la fecha.</p><p>Los investigadores han nombrado tentativamente a la especie como "Luminaris pacificus" y están realizando estudios genéticos detallados para determinar su posición en el árbol evolutivo de las especies marinas.</p><p>"Este descubrimiento nos recuerda cuánto nos falta por conocer de nuestros océanos", señaló el líder de la expedición. El equipo planea regresar a la zona para estudiar el hábitat natural de esta especie.</p>',
                'category_index' => 5, 'is_featured' => false,
            ],
            [
                'title' => 'Reforma educativa amplía el acceso a la tecnología en escuelas rurales',
                'excerpt' => 'El gobierno aprobó un plan que dotará de equipos tecnológicos y conectividad a más de 10,000 escuelas en zonas rurales.',
                'content' => '<p>El Ministerio de Educación anunció la implementación de un ambicioso programa para reducir la brecha digital en las comunidades rurales del país.</p><p>El plan contempla la instalación de laboratorios de computación, conexión a internet de alta velocidad y la capacitación de docentes en herramientas digitales educativas. Se beneficiarán más de 10,000 escuelas y 500,000 estudiantes en los próximos tres años.</p><p>"La educación de calidad no debe depender del código postal de un estudiante", declaró la ministra de Educación durante la presentación del programa. "Con esta reforma, estamos construyendo puentes hacia la igualdad de oportunidades".</p><p>El presupuesto asignado incluye fondos para mantenimiento de equipos y actualización periódica de software educativo, garantizando la sostenibilidad del proyecto a largo plazo.</p>',
                'category_index' => 7, 'is_featured' => false,
            ],
            [
                'title' => 'Festival internacional de cine celebra su vigésima edición con récord de asistencia',
                'excerpt' => 'El evento reunió a cineastas de 40 países y superó los 200,000 espectadores durante una semana de proyecciones.',
                'content' => '<p>El Festival Internacional de Cine celebró su vigésima edición con una asistencia récord que superó todas las expectativas de los organizadores.</p><p>Durante siete días, más de 200,000 cinéfilos disfrutaron de 150 películas provenientes de 40 países diferentes, incluyendo estrenos mundiales de directores consagrados y óperas primas de talentos emergentes.</p><p>La película ganadora del Gran Premio fue un drama social que aborda la migración en América Latina, dirigida por una cineasta de 32 años que recibió una ovación de pie de más de diez minutos.</p><p>"El cine sigue siendo una ventana al alma de las sociedades", expresó el director del festival. "Esta edición ha demostrado que el público tiene hambre de historias auténticas y diversas".</p>',
                'category_index' => 4, 'is_featured' => false,
            ],
            [
                'title' => 'Inflación muestra signos de desaceleración por tercer mes consecutivo',
                'excerpt' => 'Los indicadores económicos revelan una tendencia positiva con la inflación descendiendo al 3.2% anual.',
                'content' => '<p>El banco central reportó que la tasa de inflación anual descendió al 3.2%, marcando el tercer mes consecutivo de desaceleración en los precios al consumidor.</p><p>Los analistas atribuyen esta tendencia a las políticas monetarias implementadas durante el último semestre y a la estabilización de los precios internacionales de materias primas.</p><p>"Estamos viendo los resultados de una gestión macroeconómica prudente", indicó la presidenta del banco central. "Sin embargo, debemos mantener la cautela y no relajar las medidas que nos han traído hasta aquí".</p><p>Los sectores que mostraron mayor reducción de precios fueron alimentos y transporte, mientras que los servicios de salud y educación mantuvieron incrementos moderados. Los economistas proyectan que la tendencia continuará durante el próximo trimestre.</p>',
                'category_index' => 1, 'is_featured' => true,
            ],
            [
                'title' => 'Nuevo estudio revela beneficios de la meditación en la salud cardiovascular',
                'excerpt' => 'Investigación con 5,000 participantes demuestra que la práctica regular de meditación reduce significativamente el riesgo cardíaco.',
                'content' => '<p>Un estudio longitudinal realizado durante cinco años con 5,000 participantes ha proporcionado evidencia contundente sobre los beneficios de la meditación para la salud del corazón.</p><p>Los resultados, publicados en una revista médica de alto impacto, muestran que las personas que practican meditación al menos 20 minutos diarios tienen un 35% menos de probabilidades de sufrir eventos cardiovasculares.</p><p>"Los mecanismos incluyen la reducción del estrés crónico, la disminución de la presión arterial y la mejora en la calidad del sueño", explicó el cardiólogo que lideró la investigación.</p><p>Los hospitales participantes en el estudio ya están incorporando programas de meditación guiada como parte de sus protocolos de prevención cardiovascular.</p>',
                'category_index' => 6, 'is_featured' => false,
            ],
            [
                'title' => 'Startup latinoamericana revoluciona el acceso al agua potable con tecnología solar',
                'excerpt' => 'La empresa desarrolló un purificador de agua alimentado por energía solar capaz de abastecer comunidades enteras.',
                'content' => '<p>Una startup fundada por ingenieros latinoamericanos ha desarrollado un sistema innovador de purificación de agua que funciona exclusivamente con energía solar.</p><p>El dispositivo, que puede instalarse en comunidades sin acceso a la red eléctrica, es capaz de purificar hasta 10,000 litros de agua diarios, suficiente para abastecer a una comunidad de 500 personas.</p><p>La tecnología utiliza un proceso de destilación solar mejorado con membranas de nanotecnología que eliminan el 99.9% de los contaminantes, incluyendo bacterias, virus y metales pesados.</p><p>"Nuestro objetivo es que ninguna comunidad en América Latina tenga que depender de agua contaminada", afirmó la CEO de la empresa. El proyecto ha recibido reconocimiento internacional y financiamiento de organizaciones de desarrollo.</p>',
                'category_index' => 2, 'is_featured' => false,
            ],
            [
                'title' => 'Congreso aprueba ley de protección de datos personales en el ámbito digital',
                'excerpt' => 'La nueva legislación establece marcos regulatorios para el tratamiento de información personal en plataformas digitales.',
                'content' => '<p>El Congreso aprobó por amplia mayoría una nueva ley de protección de datos personales que regula el tratamiento de información en el entorno digital.</p><p>La legislación establece obligaciones claras para empresas y organizaciones que recopilan datos personales, incluyendo el derecho al olvido, la portabilidad de datos y el consentimiento informado.</p><p>Las empresas tendrán un plazo de 12 meses para adecuarse a las nuevas disposiciones, que incluyen sanciones significativas para quienes incumplan las normas de protección.</p><p>"Esta ley coloca a nuestro país a la vanguardia en materia de derechos digitales", señaló el presidente de la comisión legislativa correspondiente. Organizaciones de la sociedad civil celebraron la aprobación como un avance importante en la protección de la privacidad.</p>',
                'category_index' => 0, 'is_featured' => false,
            ],
            [
                'title' => 'Científicos logran cultivar coral resistente al cambio climático',
                'excerpt' => 'Un laboratorio marino desarrolló variedades de coral que sobreviven en aguas más cálidas y ácidas.',
                'content' => '<p>Un equipo de biólogos marinos ha logrado desarrollar variedades de coral genéticamente resistentes a las condiciones adversas provocadas por el cambio climático.</p><p>Los corales modificados pueden sobrevivir en aguas con temperaturas hasta 3 grados centígrados más altas que las actuales y niveles de acidez superiores a los proyectados para el año 2050.</p><p>Las primeras pruebas de trasplante en arrecifes naturales han mostrado tasas de supervivencia del 85%, muy superiores al 30% registrado con corales no modificados.</p><p>"Los arrecifes de coral son ecosistemas fundamentales para la biodiversidad marina y la protección costera", explicó la directora del proyecto. "Este avance nos da esperanza para su conservación frente al calentamiento global".</p>',
                'category_index' => 5, 'is_featured' => false,
            ],
            [
                'title' => 'Campeonato mundial de ajedrez juvenil corona a nuevo prodigio latinoamericano',
                'excerpt' => 'Un joven de 14 años se convirtió en el campeón mundial más joven de la historia del ajedrez juvenil.',
                'content' => '<p>El mundo del ajedrez celebra el surgimiento de un nuevo prodigio: un joven de 14 años que se coronó campeón mundial en la categoría juvenil, convirtiéndose en el ganador más joven en la historia del torneo.</p><p>El torneo, celebrado con la participación de 64 jugadores de 35 países, fue dominado por el joven latinoamericano que mostró una madurez estratégica extraordinaria para su edad.</p><p>En la partida final, el joven campeón ejecutó una brillante combinación táctica que dejó sorprendidos a los analistas más experimentados. "Su comprensión posicional es comparable a la de grandes maestros con décadas de experiencia", comentó un reconocido entrenador internacional.</p><p>El nuevo campeón dedicó su triunfo a su familia y a su entrenador, y expresó su deseo de representar a su país en futuras competiciones de élite.</p>',
                'category_index' => 3, 'is_featured' => false,
            ],
        ];

        foreach ($newsData as $index => $data) {
            News::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'image' => null,
                'image_alt' => 'Imagen ilustrativa: ' . Str::limit($data['title'], 80),
                'category_id' => $createdCategories[$data['category_index']]->id,
                'user_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                'is_featured' => $data['is_featured'],
                'views' => rand(50, 5000),
            ]);
        }
    }
}
