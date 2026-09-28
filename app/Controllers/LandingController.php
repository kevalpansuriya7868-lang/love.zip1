<?php

class LandingController {
    public function index() {
        if (is_logged_in()) {
            redirect('dashboard');
        }
        
        // Render the integrated Pint landing page
        view('landing/pint', [], false);
    }
}
