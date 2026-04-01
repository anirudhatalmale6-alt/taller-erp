<?php
class AgendaController extends Controller {

    public function index() {
        $this->requireAuth();
        $this->view('agenda/index', [
            'pageTitle' => 'Agenda',
            'extraCss' => ['https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css'],
            'extraJs' => ['https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js', 'assets/js/agenda.js'],
        ]);
    }
}
