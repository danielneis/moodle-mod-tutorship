<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * English strings for tutorship.
 *
 * This file defines all the module strings that will be shown in
 * English language by calling the get_string function, with the
 * string name as first argument and the module name as second.
 *
 * @package   mod_tutorship
 * @copyright 2010 Alejandro Michavila
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['at'] = 'a las';
$string['cancel'] = '<font color="#DF0101">Anular reserva</font>';
$string['chooseteacher'] = 'Seleccione un profesor';
$string['configdesc'] = 'Establece la descripción del periodo por defecto común a todas las instancias del módulo.';
$string['configendday'] = 'Establece el día de cierre del periodo por defecto común a todas las instancias del módulo.';
$string['configendmonth'] = 'Establece el mes de cierre del periodo por defecto común a todas las instancias del módulo.';
$string['configendtime'] = 'Seleccione una hora de fin para los días del horario.';
$string['configendyear'] = 'Establece el año de cierre del periodo por defecto común a todas las instancias del módulo.';
$string['configfirstperiod'] = 'Define los valores por defecto para el primer periodo.';
$string['configintro'] = 'Ajustes de configuración inicial.<p>Los valores de configuración establecidos aquí, son usados para ajustar la duración de la franja, la hora en la que comienza y termina un día del horario, y las fechas de apertura y cierre de los periodos por defecto.</p><p>Todos estos ajustes son comunes a todas las instancias del módulo, por lo tanto NO SE PUEDEN MODIFICAR los valores si hay alguna instancia añadida a algún curso, SÓLO SE PUEDEN MODIFICAR los valores antes de añadir las instancias a los cursos.</p>';
$string['configsecondperiod'] = 'Define los valores por defecto para el segundo periodo.';
$string['configstartday'] = 'Establece el día de comienzo del periodo por defecto común a todas las instancias del módulo.';
$string['configstartmonth'] = 'Establece el mes de comienzo del periodo por defecto común a todas las instancias del módulo.';
$string['configstarttime'] = 'Seleccione una hora en la que comenzarán los días del horario.';
$string['configstartyear'] = 'Establece el año de comienzo del periodo por defecto común a todas las instancias del módulo.';
$string['configthirdperiod'] = 'Define los valores por defecto para el tercer periodo.';
$string['configtimeslot'] = 'Establece la longitud de la franja, el número de minutos para cada sesión de tutoría.';
$string['confirm'] = '<font color="#04B404">Confirmar reserva</font>';
$string['confirmed'] = '<font color="#04B404">confirmada</font>';
$string['confirmselect'] = 'Confirmación de reserva automática';
$string['confirmselect_help'] = 'Seleccione si la confirmación de reserva debe ser manual o automática. Se recomienda escoger confirmación manual, ya que así usted es conscience de las confirmaciones.';
$string['confsettingintro'] = '<p>Recuerde que estas configuraciones se aplican a todos los <b>horarios</b>:</p>';
$string['confsettings'] = 'Ajustes de configuración';
$string['current'] = 'Semana actual';
$string['currentweek'] = 'Mostrar semana actual';
$string['day'] = 'día';
$string['disable'] = '<font color="#DF0101">Desactivar</font>';
$string['edit'] = 'Editar';
$string['empty'] = 'Vacío';
$string['enable'] = '<font color="#04B404">Activar</font>';
$string['enddate'] = 'Fecha de cierre';
$string['endday'] = 'Día de cierre';
$string['endmonth'] = 'Mes de cierre';
$string['endtime'] = 'Hora de cierre';
$string['endyear'] = 'Año de cierre';
$string['errcancelconfirm'] = 'La confirmación de reserva no pudo ser anulada, por lo que el correo no se envió al estudiante.';
$string['errconfig'] = 'Las configuraciones no han podido ser guardadas.';
$string['errconfirmation'] = 'La petición de reseva no ha podido ser confirmada, no se ha enviado el correo al estudiante.';
$string['errconfirm'] = 'La configuración de la confirmación automática no ha podido ser guardada.';
$string['erremail'] = 'No se pudo mandar el correo.'; 
$string['errinstance'] = 'Sólo se permite tener una instancia del módulo por curso.';
$string['errnotify'] = 'La configuración de envío de notificaciones no ha podido ser guardada.';
$string['errnoreserves'] = 'La configuración de desactivar reservas no ha podido ser guardada.';
$string['errperiods'] = 'Los periodos no han podido ser creados.';
$string['errperiodvalidation'] = 'Las fechas de los periodos han sido mal configuradas por parte del administrador, por favor contacte con él para resolver el problema.';
$string['errreserve'] = 'La reserva no ha podido realizarse.';
$string['errreserves'] = 'Ha alcanzado el número máximo de reservas permitidas.';
$string['errslotdelete'] = 'La franja no ha podido ser eliminada.';
$string['errtimeslots'] = 'Las franjas no han podido ser creadas.';
$string['errtimetable'] = 'La franaja no ha podido ser activada en el horario.';
$string['errunreserve'] = 'Desresevar no ha podido realizarse.';
$string['firstperioddesc'] = 'Descripción del primer periodo.';
$string['firstperiod'] = 'Primer periodo';
$string['friday'] = 'Viernes';
$string['gotoreservation'] = 'Ir a la reserva';
$string['hours'] = 'Horas';
$string['intro'] = 'Descripción';
$string['modulename_help'] = 'El módulo tutorías permite a los profesores diseñar y gestionar su horario de tutorías para ofrecer al alumnado franjas horarias semanales, y que éstos puedan solicitar las franjas disponibles.';
$string['modulenameplural'] = 'Tutorías';
$string['modulename'] = 'Tutorías';
$string['monday'] = 'Lunes';
$string['month'] = 'Mes';
$string['nameandvisibility'] = 'nombre y visibilidad de Tutorías';
$string['name_help'] = 'Introduzca el nombre corto de la instancia de tutorías, que quiera que se muestre en el curso.';
$string['name'] = 'Nombre';
$string['next'] = 'Próxima semana';
$string['nextweek'] = 'Mostrar próxima semana';
$string['noreserves_help'] = 'Seleccione si quiere desactivar las reservas o no. Si son desactivadas, los estudiantes sólo podrán ver el horario y no podrán reservar ninguna hora.';
$string['noreserves'] = 'Desactivar reservas';
$string['notconfirmed'] = '<font color="#DF0101">sin confirmar</font>';
$string['noteachers'] = 'No existe ningún profesor en este curso.';
$string['notifyselect_help'] = 'Seleccione si quiere que se le notifique por correo cuando se solicite una franja de tutoría. Se recomienda que si, ya que el correo incluye un enlace al horario.';
$string['notifyselect'] = 'Envío de notificaciones';
$string['notimetable'] = 'no tiene horario de tutorías.';
$string['periodselect_help'] = 'Seleccione de la lista el periodo del cual quiera ver o editar el horario correspondiente.';
$string['periodselect'] = 'Selección de periodo';
$string['pluginadministration'] = 'Administración del plugin';
$string['reservationcancelled'] = 'Petición de reserva de tutoría anulada';
$string['reservationcancelledtxt'] = 'La petición de reserva de tutoría ha sido anulada por';
$string['reservationconfirmed'] = 'Petición de reserva de tutoría confirmada';
$string['reservationconfirmedtxt'] = 'La petición de reserva ha sido confirmada por';
$string['reservationdetails'] = 'Detalles de la reserva: ';
$string['reservationrequest'] = 'Petición de reserva de tutoría';
$string['reservationrequesttxt'] = 'Petición de reserva de tutoría, por favor confirme o anule dicha petición haciendo click en el siguiente enlace, que le llevará al horario, donde podrá confirmar o anular la reserva, tras pedirle su usuario y contraseña. <br>La tutoría ha sido solicitada por';
$string['reserved'] = '<font color="#A4A4A4">Reservado</font>';
$string['reserve'] = 'Reservar';
$string['reservesselect_help'] = 'Seleccione de la lista el número máximo de reservas que puede realizar el alumno, en el periodo de dos semanas.';
$string['reservesselect'] = 'Seleccione el número máximo de reservas';
$string['secondperioddesc'] = 'Descripción del segundo periodo';
$string['secondperiod'] = 'Segundo periodo';
$string['singletutorship'] = 'Tutoría';
$string['startdate'] = 'Fecha de comienzo';
$string['startday'] = 'Día de comienzo';
$string['startmonth'] = 'Mes de comienzo';
$string['starttime'] = 'Hora de comienzo';
$string['startyear'] = 'Año de comienzo';
$string['studentheading_help'] = 'Esta es la vista del módulo Tutorías, donde se muestra el horario de tutorías del profesor.<p>Se puede ver la semana actual y próxima del horario, y se puede reservar las franjas disponibles, hasta un cierto límite, y nunca se podrán solicitar o reservar franjas del mismo día.</p>';
$string['studentheading'] = 'Ayuda';
$string['teacherheading_help'] = 'Esta es la vista del módulo Tutorías, donde el profesor puede editar su horario u horarios de tutorías y puede verlo de la forma que lo ve un alumno, a parte de poder ver las peticiones de reserva para poder confirmarlas o anularlas.<p>Eligiendo entre los tres periodos se puede editar o ver cualquier horario relativo a esos periodos, por lo que el profesor puede dejar configurado todos los horarios a principio de curso.</p><p>También se puede configurar ciertos parámetros de configuración relativos al horario, como son el número máximo de solicitudes de reserva de tutorías por alumno, la confirmación automática o manual de esas solicitudes, el envío o no de esas notificaciones a través del correo, y la posibilidad de desactivar que los alumnos puedan reservar.</p>';
$string['teacherheading'] = 'Ayuda';
$string['teacherselect_help'] = 'Si quiere solicitar una reserva de tutoría, primero eliga el profesor, y a continuación verá su horario de tutorías, donde verá y podrá solicitar las franjas disponibles, hasta cierto límite';
$string['teacherselect'] = 'Seleccione un profesor';
$string['thirdperioddesc'] = 'Descripción del tercer periodo';
$string['thirdperiod'] = 'Tercer periodo';
$string['thursday'] = 'Jueves';
$string['timeslotlength'] = 'Longitud de la franja (minutos)';
$string['tuesday'] = 'Martes';
$string['tutoringschedule'] = 'Horario de tutorías';
$string['tutorshipadministration'] = 'Administrar tutorías';
$string['tutorship:reserve'] = 'Reservar tutorías';
$string['tutorship'] = 'Tutorías';
$string['tutorship:update'] = 'Actualizar tutorías';
$string['tutorship:view'] = 'Ver tutorías';
$string['unreserve'] = 'Anular reserva';
$string['view'] = 'Ver';
$string['wednesday'] = 'Miércoles';
$string['year'] = 'Año';
