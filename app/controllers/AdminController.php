<?php
class AdminController {

    private function guard(): void {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
    }

    public function index(): void {
        $this->guard();
        echo twig_render('admin/admin.html.twig', []);
    }

    public function entreprises(): void {
        $this->guard();
        echo twig_render('admin/admin.html.twig', []);
    }
}
