# Informes
Informe técnico de los cursos en la plataforma Moodle.

En este repositorio encontraras el aplicativo desarrollado por el departamento UNIAJCVirtual, el cual permite realizar 4 tipos de informes a la plataforma Moodle los cuales son: 

Alistamiento: Consiste en realizar ciertas configuraciones de tu curso en la plataforma Moodle, siguiendo las estructuras planteadas por la institución; dichas estructuras cuentan de cuatro aspectos importantes: Información del profesor, foro de consultas, fechas de las unidades y libro de calificaciones, cada uno de estos aspectos cuenta con un item especifico queda como resultado: CUMPLE, NO CUMPLE o NO APLICA.

Avance formativo 1 y 2 : Consiste en revisar las categorías del libro de calificaciones con los nombres "Avance formativo 1" y "Avance formativo 2"; revisar si las actividades, foros o cuestionarios tienen calificaciones y retroalimentaciones.

Estadísticas: Nos brinda la información de la cantidad de estudiantes y profesores en los diferentes cursos en la plataforma Moodle.

```mermaid
graph TB
    User((User))
    
    subgraph "Web Application"
        subgraph "Frontend Container"
            WebUI["Web Interface<br>HTML/Bootstrap"]
            
            subgraph "Frontend Components"
                DataTable["DataTable Component<br>jQuery DataTables"]
                Forms["Form Handler<br>JavaScript"]
                AlertSystem["Alert System<br>SweetAlert2"]
                StyleSystem["Styling System<br>CSS"]
            end
        end
        
        subgraph "Backend Container"
            PHPEngine["PHP Application Engine<br>PHP"]
            
            subgraph "Core Components"
                ReportController["Report Controller<br>PHP"]
                ConnectionManager["Connection Manager<br>PHP/mysqli"]
                ModelLayer["Model Layer<br>PHP Classes"]
            end
            
            subgraph "Report Components"
                EnlistmentReport["Enlistment Report<br>PHP"]
                AdvanceReport["Advance Report<br>PHP"]
                StatisticsReport["Statistics Report<br>PHP"]
                InstitutionalReport["Institutional Report<br>PHP"]
            end
            
            subgraph "Helper Components"
                StringHelper["String Helper<br>PHP"]
                QueryBuilder["Query Builder<br>PHP"]
                DataFormatter["Data Formatter<br>PHP"]
            end
        end
        
        subgraph "Data Container"
            MoodleDB[("Moodle Database<br>MySQL/MariaDB")]
            
            subgraph "Database Components"
                CourseData["Course Tables<br>MySQL"]
                UserData["User Tables<br>MySQL"]
                GradeData["Grade Tables<br>MySQL"]
                CategoryData["Category Tables<br>MySQL"]
            end
        end
    end

    %% Frontend Relationships
    User -->|"Accesses"| WebUI
    WebUI -->|"Uses"| DataTable
    WebUI -->|"Uses"| Forms
    WebUI -->|"Uses"| AlertSystem
    WebUI -->|"Styled by"| StyleSystem
    
    %% Backend Relationships
    WebUI -->|"Submits requests to"| ReportController
    ReportController -->|"Uses"| ConnectionManager
    ReportController -->|"Uses"| ModelLayer
    ConnectionManager -->|"Connects to"| MoodleDB
    
    %% Report Component Relationships
    ReportController -->|"Generates"| EnlistmentReport
    ReportController -->|"Generates"| AdvanceReport
    ReportController -->|"Generates"| StatisticsReport
    ReportController -->|"Generates"| InstitutionalReport
    
    %% Helper Relationships
    EnlistmentReport -->|"Uses"| StringHelper
    AdvanceReport -->|"Uses"| QueryBuilder
    StatisticsReport -->|"Uses"| DataFormatter
    
    %% Database Relationships
    MoodleDB -->|"Contains"| CourseData
    MoodleDB -->|"Contains"| UserData
    MoodleDB -->|"Contains"| GradeData
    MoodleDB -->|"Contains"| CategoryData
    
    %% Data Access Relationships
    ModelLayer -->|"Queries"| CourseData
    ModelLayer -->|"Queries"| UserData
    ModelLayer -->|"Queries"| GradeData
    ModelLayer -->|"Queries"| CategoryData
```