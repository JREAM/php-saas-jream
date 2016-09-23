<?php
namespace Admin;
use \Phalcon\Tag,
    \Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

class TransactionController extends \BaseController
{

    /**
     * @return void
     */
    public function onConstruct()
    {
        if (!$this->session->has('id') || $this->session->get('role') != 'admin') {
            $this->redirect('index');
        }

        parent::initialize();
        Tag::setTitle('Admin');
    }

    // --------------------------------------------------------------

    /**
     * @return void
     */
    public function indexAction()
    {
        $page = (int) $this->request->get("page");
        $page = (!$page) ? 1 : (int) $page;

        $builder = $this->modelsManager->createBuilder()
            ->columns('id, user_id, transaction_id, type, gateway, amount, amount_after_discount, created_at')
            ->from('Transaction')
            ->orderBy('id');

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder([
            "builder" => $builder,
            "limit"=> 50,
            "page" => $page
        ]);

        // Get the paginated results
        $page = $paginator->getPaginate();

        $this->view->setVars([
            'form' => new \AdminTransactionForm(),
            'page' => $page,
            'transactions' => \Transaction::find(),
            'users' => \User::find(),
            'products' => \Product::find(),
            // CSRF
            'tokenKey' => $this->security->getTokenKey(),
            'token' => $this->security->getToken()
        ]);

        $this->view->pick('admin/transaction');
    }

    // --------------------------------------------------------------

    public function createAction()
    {

        try {

            $transactionManager = new TransactionManager();

            $transaction                 = new \Transaction();
            $transaction->user_id        = $this->request->getPost('user_id');
            $transaction->transaction_id = $this->request->getPost('transaction_id');
            $transaction->type           = 'purchase';
            $transaction->gateway        = 'manual';
            $transaction->amount         = $this->request->getPost('amount');

            if (!$transaction->save() == false) {
                $transactionManager->rollBack('Error Saving Transaction');
            }

            $userPurchase                 = new \UserPurchase();
            $userPurchase->user_id        = $this->request->getPost('user_id');
            $userPurchase->product_id     = $this->request->getPost('product_id');
            $userPurchase->transaction_id = $transaction->id;

            if ($userPurchase->save() == false) {
                $transactionManager->rollBack('Error Saving User Purchase');
            }

            $transactionManager->commit();

            $this->flash->success('Transaction Created');
        }
        catch (Phalcon\Mvc\Model\Transaction\Failed $e){
            $this->flash->error($e->getMessage());
        }

        $this->response->redirect('admin/transaction');

    }

    // --------------------------------------------------------------

}

// End of File
// --------------------------------------------------------------