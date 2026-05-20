<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $posts = [
            [
                'title' => 'Inclusive Marketing: Why and How Does it Work?',
                'content' => 'Learn how inclusive marketing helps brands reach wider audiences and build trust through authentic messaging and accessible campaigns.',
                'category' => 'Digital',
                'badge' => 'bg-info shadow-info',
                'image' => '05.jpg',
                'date' => 'Oct 9, 2023',
            ],
            [
                'title' => 'A Study on Smartwatch Design Qualities',
                'content' => 'We explore what users value most in smartwatch design — from battery life and display clarity to comfort and health-tracking accuracy.',
                'category' => 'Business',
                'badge' => 'bg-warning shadow-warning',
                'image' => null,
                'date' => 'Sep 3, 2023',
            ],
            [
                'title' => 'This Week in Search: New Limits and Features',
                'content' => 'A quick roundup of the latest search engine updates, ranking signals, and tools that affect how content is discovered online.',
                'category' => 'Technology',
                'badge' => 'bg-danger shadow-danger',
                'image' => '03.jpg',
                'date' => 'Sep 16, 2023',
            ],
            [
                'title' => 'How Agile is Your Forecasting Process?',
                'content' => 'Startups share practical tips for forecasting revenue and capacity when requirements change every sprint.',
                'category' => 'Startups',
                'badge' => 'bg-success shadow-success',
                'image' => null,
                'date' => 'Sep 10, 2023',
            ],
            [
                'title' => 'Why UX Design Matters and How it Affects Ranking',
                'content' => 'Good UX keeps visitors engaged longer, reduces bounce rates, and indirectly supports stronger SEO performance over time.',
                'category' => 'Business',
                'badge' => 'bg-warning shadow-warning',
                'image' => '02.jpg',
                'date' => 'Aug 19, 2023',
            ],
            [
                'title' => 'This Long-Awaited Technology May Change the World',
                'content' => 'Emerging platforms promise faster workflows for teams, but adoption still depends on integration, training, and clear ROI.',
                'category' => 'Digital',
                'badge' => 'bg-info shadow-info',
                'image' => null,
                'date' => 'Sep 3, 2023',
            ],
            [
                'title' => '5 Bad Landing Page Examples & How to Fix Them',
                'content' => 'Common landing page mistakes include weak headlines, slow load times, and missing calls to action — here is how to fix them.',
                'category' => 'Startups',
                'badge' => 'bg-success shadow-success',
                'image' => '01.jpg',
                'date' => 'Sep 3, 2023',
            ],
            [
                'title' => 'Building a Symfony Blog Without a Database',
                'content' => 'A demo approach using hardcoded post arrays, Twig templates, and the Silicon UI kit for a simple junior portfolio project.',
                'category' => 'Technology',
                'badge' => 'bg-danger shadow-danger',
                'image' => '04.jpg',
                'date' => 'May 15, 2026',
            ],
            [
                'title' => 'Silicon Template for Junior Projects',
                'content' => 'The Silicon Bootstrap template provides ready-made blog layouts, sidebars, and footers that pair well with Symfony routing.',
                'category' => 'Digital',
                'badge' => 'bg-info shadow-info',
                'image' => null,
                'date' => 'May 10, 2026',
            ],
            [
                'title' => 'Docker and Traefik for Local Symfony',
                'content' => 'Run Symfony behind HTTPS locally with Docker Compose, named volumes for vendor, and Traefik host rules.',
                'category' => 'Business',
                'badge' => 'bg-warning shadow-warning',
                'image' => '06.jpg',
                'date' => 'May 5, 2026',
            ],
        ];

        return $this->render('page/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }
}
