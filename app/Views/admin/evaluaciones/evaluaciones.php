<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    
    <!-- Estadísticas generales -->
    <div class="row">
        <div class="col-md-12">
            <div class="summary-stats">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number text-info">6</div>
                            <div class="text-muted">Total Capacitaciones</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number text-success">3</div>
                            <div class="text-muted">Evaluaciones Completadas</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number text-warning">2</div>
                            <div class="text-muted">En Progreso</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number text-danger">1</div>
                            <div class="text-muted">Pendientes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de capacitaciones con evaluaciones -->
    <div class="row">
        <div class="col-md-12">
            
            <!-- Capacitación 1: Liderazgo y Trabajo en Equipo -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training1" aria-expanded="false">
                    <h5>Capacitación: Liderazgo y Trabajo en Equipo</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 15 de Marzo, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 4 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 25 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-completed me-3">Completada</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training1">
                    <form class="evaluation-form" data-training="1">
                        
                        <!-- Pregunta 1 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">1</span>
                                ¿Cómo calificaría la calidad del contenido de la capacitación?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q1_1" name="question_1" value="5">
                                    <label for="q1_1">Excelente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q1_2" name="question_1" value="4" checked>
                                    <label for="q1_2">Muy Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q1_3" name="question_1" value="3">
                                    <label for="q1_3">Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q1_4" name="question_1" value="2">
                                    <label for="q1_4">Regular</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q1_5" name="question_1" value="1">
                                    <label for="q1_5">Deficiente</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 2 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">2</span>
                                ¿El facilitador demostró dominio del tema?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q2_1" name="question_2" value="5" checked>
                                    <label for="q2_1">Totalmente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_2" name="question_2" value="4">
                                    <label for="q2_2">En gran medida</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_3" name="question_2" value="3">
                                    <label for="q2_3">Moderadamente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_4" name="question_2" value="2">
                                    <label for="q2_4">Poco</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_5" name="question_2" value="1">
                                    <label for="q2_5">Nada</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 3 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">3</span>
                                ¿Qué aspecto de la capacitación le resultó más útil?
                            </div>
                            <div class="text-answer">
                                <textarea class="form-control" name="question_3" rows="4" placeholder="Escriba su respuesta aquí...">Los ejercicios prácticos de liderazgo situacional fueron muy efectivos para entender cómo adaptar el estilo de liderazgo según el equipo y la situación.</textarea>
                            </div>
                        </div>
                        
                        <!-- Pregunta 4 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">4</span>
                                ¿Recomendaría esta capacitación a otros colegas?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q4_1" name="question_4" value="5" checked>
                                    <label for="q4_1">Definitivamente sí</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q4_2" name="question_4" value="4">
                                    <label for="q4_2">Probablemente sí</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q4_3" name="question_4" value="3">
                                    <label for="q4_3">Neutral</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q4_4" name="question_4" value="2">
                                    <label for="q4_4">Probablemente no</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q4_5" name="question_4" value="1">
                                    <label for="q4_5">Definitivamente no</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 5 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">5</span>
                                Comentarios adicionales o sugerencias de mejora
                            </div>
                            <div class="text-answer">
                                <textarea class="form-control" name="question_5" rows="4" placeholder="Escriba sus comentarios y sugerencias aquí...">Sería útil incluir más casos prácticos del sector público y extender la duración a 6 horas para profundizar más en los temas.</textarea>
                            </div>
                        </div>
                        
                    </form>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Evaluación completada el 16 de Marzo, 2024
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-success">
                                <i class="fas fa-download"></i> Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Capacitación 2: Comunicación Efectiva -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training2" aria-expanded="false">
                    <h5>Capacitación: Comunicación Efectiva en el Ámbito Laboral</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 22 de Marzo, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 3 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 30 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-in-progress me-3">En Progreso</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training2">
                    <form class="evaluation-form" data-training="2">
                        
                        <!-- Pregunta 1 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">1</span>
                                ¿Cómo calificaría la claridad de la presentación del facilitador?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q2_1_1" name="training2_question_1" value="5" checked>
                                    <label for="q2_1_1">Excelente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_1_2" name="training2_question_1" value="4">
                                    <label for="q2_1_2">Muy Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_1_3" name="training2_question_1" value="3">
                                    <label for="q2_1_3">Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_1_4" name="training2_question_1" value="2">
                                    <label for="q2_1_4">Regular</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_1_5" name="training2_question_1" value="1">
                                    <label for="q2_1_5">Deficiente</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 2 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">2</span>
                                ¿Los ejemplos utilizados fueron relevantes para su trabajo?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q2_2_1" name="training2_question_2" value="5">
                                    <label for="q2_2_1">Totalmente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_2_2" name="training2_question_2" value="4" checked>
                                    <label for="q2_2_2">En gran medida</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_2_3" name="training2_question_2" value="3">
                                    <label for="q2_2_3">Moderadamente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_2_4" name="training2_question_2" value="2">
                                    <label for="q2_2_4">Poco</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_2_5" name="training2_question_2" value="1">
                                    <label for="q2_2_5">Nada</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 3 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">3</span>
                                ¿Qué técnicas de comunicación aprendidas implementará en su trabajo?
                            </div>
                            <div class="text-answer">
                                <textarea class="form-control" name="training2_question_3" rows="4" placeholder="Escriba su respuesta aquí..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Pregunta 4 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">4</span>
                                ¿La duración de la capacitación fue adecuada?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q2_4_1" name="training2_question_4" value="1">
                                    <label for="q2_4_1">Muy corta</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_4_2" name="training2_question_4" value="2">
                                    <label for="q2_4_2">Corta</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_4_3" name="training2_question_4" value="3">
                                    <label for="q2_4_3">Adecuada</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_4_4" name="training2_question_4" value="4">
                                    <label for="q2_4_4">Larga</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q2_4_5" name="training2_question_4" value="5">
                                    <label for="q2_4_5">Muy larga</label>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Evaluación parcialmente completada
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 60%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-warning me-2" onclick="saveProgress(2)">
                                <i class="fas fa-save"></i> Guardar Progreso
                            </button>
                            <button type="button" class="btn btn-success" onclick="completeEvaluation(2)">
                                <i class="fas fa-check"></i> Completar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Capacitación 3: Gestión del Tiempo -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training3" aria-expanded="false">
                    <h5>Capacitación: Gestión del Tiempo y Productividad</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 28 de Marzo, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 5 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 20 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-pending me-3">Pendiente</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training3">
                    <form class="evaluation-form" data-training="3">
                        
                        <!-- Pregunta 1 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">1</span>
                                ¿Cómo calificaría el contenido sobre técnicas de gestión del tiempo?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q3_1_1" name="training3_question_1" value="5">
                                    <label for="q3_1_1">Excelente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_1_2" name="training3_question_1" value="4">
                                    <label for="q3_1_2">Muy Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_1_3" name="training3_question_1" value="3">
                                    <label for="q3_1_3">Bueno</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_1_4" name="training3_question_1" value="2">
                                    <label for="q3_1_4">Regular</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_1_5" name="training3_question_1" value="1">
                                    <label for="q3_1_5">Deficiente</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 2 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">2</span>
                                ¿Los materiales proporcionados fueron útiles?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q3_2_1" name="training3_question_2" value="5">
                                    <label for="q3_2_1">Muy útiles</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_2_2" name="training3_question_2" value="4">
                                    <label for="q3_2_2">Útiles</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_2_3" name="training3_question_2" value="3">
                                    <label for="q3_2_3">Moderadamente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_2_4" name="training3_question_2" value="2">
                                    <label for="q3_2_4">Poco útiles</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_2_5" name="training3_question_2" value="1">
                                    <label for="q3_2_5">Inútiles</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 3 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">3</span>
                                ¿Qué herramientas de productividad le parecieron más interesantes?
                            </div>
                            <div class="text-answer">
                                <textarea class="form-control" name="training3_question_3" rows="4" placeholder="Escriba su respuesta aquí..."></textarea>
                            </div>
                        </div>
                        
                        <!-- Pregunta 4 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">4</span>
                                ¿Cómo evaluaría la metodología utilizada en la capacitación?
                            </div>
                            <div class="rating-group">
                                <div class="rating-option">
                                    <input type="radio" id="q3_4_1" name="training3_question_4" value="5">
                                    <label for="q3_4_1">Excelente</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_4_2" name="training3_question_4" value="4">
                                    <label for="q3_4_2">Muy Buena</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_4_3" name="training3_question_4" value="3">
                                    <label for="q3_4_3">Buena</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_4_4" name="training3_question_4" value="2">
                                    <label for="q3_4_4">Regular</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" id="q3_4_5" name="training3_question_4" value="1">
                                    <label for="q3_4_5">Deficiente</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pregunta 5 -->
                        <div class="question-section">
                            <div class="question-title">
                                <span class="question-number">5</span>
                                Sugerencias para futuras capacitaciones sobre gestión del tiempo
                            </div>
                            <div class="text-answer">
                                <textarea class="form-control" name="training3_question_5" rows="4" placeholder="Escriba sus sugerencias aquí..."></textarea>
                            </div>
                        </div>
                        
                    </form>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            Evaluación no iniciada
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="startEvaluation(3)">
                                <i class="fas fa-play"></i> Iniciar Evaluación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Capacitación 4: Atención al Cliente -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training4" aria-expanded="false">
                    <h5>Capacitación: Excelencia en Atención al Cliente</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 5 de Abril, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 4 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 35 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-completed me-3">Completada</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training4">
                    <div class="alert alert-success m-3">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>¡Evaluación Completada!</strong>
                        <br>
                        Completaste exitosamente la evaluación el 6 de Abril, 2024 a las 14:30.
                        <br>
                        <small class="text-muted">Puntuación promedio: 4.6/5.0</small>
                    </div>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Evaluación destacada
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-info me-2">
                                <i class="fas fa-eye"></i> Ver Respuestas
                            </button>
                            <button type="button" class="btn btn-outline-success">
                                <i class="fas fa-download"></i> Certificado
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Capacitación 5: Innovación y Creatividad -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training5" aria-expanded="false">
                    <h5>Capacitación: Innovación y Creatividad en el Trabajo</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 12 de Abril, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 6 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 18 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-in-progress me-3">En Progreso</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training5">
                    <div class="alert alert-info m-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Evaluación Parcial</strong>
                        <br>
                        Has completado 2 de 6 preguntas. Puedes continuar donde lo dejaste.
                        <br>
                        <small class="text-muted">Última actualización: Hace 2 horas</small>
                    </div>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-clock text-info me-2"></i>
                            Progreso guardado automáticamente
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 33%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-info">
                                <i class="fas fa-play"></i> Continuar Evaluación
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Capacitación 6: Ética Profesional -->
            <div class="training-card">
                <div class="training-header collapsed" data-bs-toggle="collapse" data-bs-target="#training6" aria-expanded="false">
                    <h5>Capacitación: Ética Profesional y Responsabilidad Social</h5>
                    <div class="training-info">
                        <div>
                            <small><i class="fas fa-calendar-alt me-1"></i> 20 de Abril, 2024</small>
                            <small class="ms-3"><i class="fas fa-clock me-1"></i> 3 horas</small>
                            <small class="ms-3"><i class="fas fa-users me-1"></i> 40 participantes</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="training-status status-completed me-3">Completada</span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="collapse evaluation-content" id="training6">
                    <div class="alert alert-success m-3">
                        <i class="fas fa-medal me-2"></i>
                        <strong>¡Evaluación Sobresaliente!</strong>
                        <br>
                        Completaste la evaluación con puntuación perfecta el 20 de Abril, 2024.
                        <br>
                        <small class="text-muted">Puntuación: 5.0/5.0 - ¡Felicitaciones!</small>
                    </div>
                    
                    <div class="evaluation-actions">
                        <div class="text-muted">
                            <i class="fas fa-star text-warning me-2"></i>
                            Desempeño excepcional
                        </div>
                        <div class="progress-indicator">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-warning me-2">
                                <i class="fas fa-trophy"></i> Ver Reconocimiento
                            </button>
                            <button type="button" class="btn btn-outline-success">
                                <i class="fas fa-download"></i> Certificado Premium
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Botón flotante para guardar progreso general -->
<div class="save-progress">
    <button type="button" class="btn btn-success btn-lg rounded-pill shadow" onclick="saveAllProgress()">
        <i class="fas fa-save"></i>
    </button>
</div>

<script>
// JavaScript para funcionalidad de evaluaciones
document.addEventListener('DOMContentLoaded', function() {
    // Manejar el colapso de las tarjetas
    const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');
    
    collapseElements.forEach(element => {
        element.addEventListener('click', function() {
            const icon = this.querySelector('.collapse-icon');
            if (this.classList.contains('collapsed')) {
                icon.style.transform = 'rotate(0deg)';
                this.classList.remove('collapsed');
            } else {
                icon.style.transform = 'rotate(-90deg)';
                this.classList.add('collapsed');
            }
        });
    });
    
    // Auto-guardar progreso cada 30 segundos
    setInterval(() => {
        autoSaveProgress();
    }, 30000);
});

function startEvaluation(trainingId) {
    console.log('Iniciando evaluación para capacitación:', trainingId);
    // Aquí iría la lógica para iniciar la evaluación
    alert('Iniciando evaluación...');
}

function saveProgress(trainingId) {
    console.log('Guardando progreso para capacitación:', trainingId);
    
    // Simular guardado
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-check"></i> Guardado';
        button.classList.remove('btn-warning');
        button.classList.add('btn-success');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-warning');
            button.disabled = false;
        }, 2000);
    }, 1500);
}

function completeEvaluation(trainingId) {
    console.log('Completando evaluación para capacitación:', trainingId);
    
    // Validar que todas las preguntas estén respondidas
    const form = document.querySelector(`form[data-training="${trainingId}"]`);
    const requiredInputs = form.querySelectorAll('input[type="radio"][name*="question"]:checked, textarea[name*="question"]');
    
    if (requiredInputs.length === 0) {
        alert('Por favor complete al menos una pregunta antes de enviar.');
        return;
    }
    
    // Simular envío
    const button = event.target;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    button.disabled = true;
    
    setTimeout(() => {
        alert('¡Evaluación completada exitosamente!');
        location.reload(); // Recargar para mostrar el nuevo estado
    }, 2000);
}

function saveAllProgress() {
    console.log('Guardando todo el progreso...');
    
    const button = document.querySelector('.save-progress button');
    const originalContent = button.innerHTML;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    setTimeout(() => {
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-success');
        
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('btn-outline-success');
            button.classList.add('btn-success');
            button.disabled = false;
        }, 2000);
    }, 1500);
}

function autoSaveProgress() {
    // Auto-guardar progreso sin notificar al usuario
    console.log('Auto-guardado de progreso...');
    
    // Aquí iría la lógica para enviar los datos al servidor
    // usando fetch() o XMLHttpRequest
}

// Función para manejar cambios en formularios
document.addEventListener('change', function(e) {
    if (e.target.matches('input[type="radio"], textarea')) {
        // Marcar como modificado para auto-guardado
        const form = e.target.closest('form');
        if (form) {
            form.setAttribute('data-modified', 'true');
        }
    }
});
</script>

<?= $this->endSection() ?>-primary me-2">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button type="button" class="btn btn-outline