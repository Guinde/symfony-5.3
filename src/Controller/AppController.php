<?

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController {

    public function home() {
        return $this->render('app/home.html.twig');
    }

    public function about() {
        return $this->render('app/about.html.twig');
    }

    public function contact() {
        return $this->render('app/contact.html.twig');
    }
}