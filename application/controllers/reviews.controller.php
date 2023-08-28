<?php
class Reviews_Controller extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index($subject = null, $review = null)
    {
        # secure arguments
        $this->secure(func_num_args(), 2);
        
        # initiate model
        $this->model('reviews');

        # invalid subject
        if (!empty($subject) && !$this->model->exists($subject)){
            $this->error(404);
        }

        # invalid review
        if (!empty($review) && !$this->model->exists($subject, $review)){
            $this->error(404);
        }

        # return review view
        if (!empty($review) && $this->model->exists($subject, $review)){
            $this->view('reviews/review', [
                'subject' => $this->model->subject($subject),
                'review' => $this->model->review($subject, $review),
                'top-offers' => $this->model->top_offers(),
            ]);
        # return subject view
        } elseif (!empty($subject) && $this->model->exists($subject)){
            $this->view('reviews/subject', [
                'subject' => $this->model->subject($subject),
                'top-offers' => $this->model->top_offers(),
                'prompts' => $this->model->prompts(),
                'faq' => $this->model->faq(),
            ]);
        # return index view
        } else {
            $this->view('reviews/index', [
                'offers' => $this->model->offers(),
                'filter' => $this->model->filter(),
                'categories' => $this->model->categories(),
                'prompts' => $this->model->prompts(),
                'prompts_count' => $this->model->prompts(),
                'tooltip' => $this->functions->tooltip()
            ]);
        }

        # display content
        $this->view->render();
    }
}