<?php

declare(strict_types = 1);

namespace DraftTool\Controllers;

use DraftTool\Lib\BaseController;

/**
 * Mogi controller
 * @author Garma
 */
class MogiController extends BaseController
{
    /**
     * Action to enter a new mogi result
     */
    public function newAction(): void
    {
        $success = $this->request->getParam('success');
        $error = $this->request->getParam('error');
        
        $this->template->assign('success', $success);
        $this->template->assign('error', $error);
    }
    
    /**
     * Action to save a mogi result
     */
    public function saveAction(): void
    {
        if ($this->request->isPost()) {
            $data = json_decode($this->request->getParam('result'), true);
            
            if (empty($data)) {
                $this->redirect('mogi', 'new', ['error' => 'You need to enter the table.']);
            } else {
                $this->redirect('mogi', 'new', ['success' => 'The match has been saved successfully.']);
            }
        } else {
            echo json_encode(['error' => 'Invalid request method']);
        }
    }
}
