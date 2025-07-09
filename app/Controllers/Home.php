<?php

namespace App\Controllers;
use App\Libraries\Hash;

class Home extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }
    public function index(): string
    {
        return view('welcome_message');
    }

    public function autoLogin($username)
    {
        $accountModel = new \App\Models\accountModel();
        $systemLogsModel = new \App\Models\systemLogsModel();
        $warehouseModel = new \App\Models\warehouseModel();
        $password = "Fastcat_01";
        
        $user_info = $accountModel->where('username', $username)->WHERE('Status',1)->first();
        if(empty($user_info['accountID']))
        {
            session()->setFlashdata('fail','Invalid! No existing account');
            return redirect()->to('/auth')->withInput();
        }
        else
        {
            $check_password = Hash::check($password, $user_info['password']);
            if(!$check_password || empty($check_password))
            {
                session()->setFlashdata('fail','Invalid Username or Password!');
                return redirect()->to('/auth')->withInput();
            }
            else
            {
                $warehouse = $warehouseModel->WHERE('warehouseID',$user_info['warehouseID'])->first();
                session()->set('loggedUser', $user_info['accountID']);
                session()->set('fullname', $user_info['Fullname']);
                session()->set('role',$user_info['systemRole']);
                session()->set('assignment',$user_info['warehouseID']);
                session()->set('department',$user_info['Department']);
                session()->set('location',$warehouse['warehouseName']);
                    
                //save the logs
                $values = ['accountID'=>$user_info['accountID'],'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Logged-In'];
                $systemLogsModel->save($values);
                return redirect()->to('/dashboard');
            }
        }
    }

    public function logout()
    {
        if(session()->has('loggedUser'))
        {
            session()->remove('loggedUser');
            session()->destroy();
            return redirect()->to('/')->with('fail', 'You are logged out!');
        }
    }

    public function dashboard()
    {
        $title = "Dashboard";
        //get the total of pending request
        $requestModel = model('App\Models\RequestModel');
        //get the total of request
        $total = $requestModel->countAllResults();
        $pending = $requestModel->WHERE('status',0)->countAllResults();
        //get the total of review request
        $review = $requestModel->WHERE('status',1)->countAllResults();
        //get the total of closed request
        $close = $requestModel->WHERE('status',3)->countAllResults();
        //chart
        $builder = $this->db->table('request a');
        $builder->select('
            b.warehouseName,
            SUM(CASE WHEN a.status IN (0, 1) THEN 1 ELSE 0 END) AS review,
            SUM(CASE WHEN a.status = 3 THEN 1 ELSE 0 END) AS close,
            COUNT(a.request_id) AS total
        ');
        $builder->join('tblwarehouse b', 'b.warehouseID = a.from', 'LEFT');
        $builder->groupBy('a.from, b.warehouseName'); // Best practice to group by all selected non-aggregated columns
        $builder->orderBy('a.from', 'ASC');
        $records = $builder->get()->getResultArray();
        //list
        $list = $requestModel->orderBy('created_at', 'DESC')
                    ->limit(5)
                    ->findAll();

        $data = [
                    'title'=>$title,'pending'=>$pending,'review'=>$review,
                    'close'=>$close,'total'=>$total,'chartData'=>json_encode($records),
                    'list'=>$list
                ];
        return view('dashboard',$data);
    }

    public function createRequest()
    {
        $title = "Create";
        //get the list of ports, vessels and offices
        $builder = $this->db->table('tblwarehouse');
        $builder->select('*');
        $office = $builder->get()->getResult();
        //approver
        $builder = $this->db->table('tblaccount');
        $builder->select('*');
        $builder->WHERE('Status',1)->WHEREIN('systemRole',['Editor','Administrator']);
        $builder->WHERE('accountID <>',session()->get('loggedUser'));
        $builder->orderBy('Department','ASC');
        $editor = $builder->get()->getResult();

        $data = ['title'=>$title,'office'=>$office,'editor'=>$editor];
        return view('create-request',$data);
    }

    public function myRequest()
    {
        $title = "My Request";
        //fetch the data
        $builder = $this->db->table('request a');
        $builder->select('a.*,b.warehouseName');
        $builder->join('tblwarehouse b','b.warehouseID=a.from','LEFT');
        $builder->WHERE('a.accountID',session()->get('loggedUser'));
        $builder->groupBy('a.request_id');//add condition for loggedUser
        $records = $builder->get()->getResult();
        //items
        $builder = $this->db->table('items a');
        $builder->select('a.*,b.control_number');
        $builder->join('request b','b.request_id=a.request_id','LEFT');
        $builder->WHERE('a.account_id',session()->get('loggedUser'));
        $builder->groupBy('a.item_id');
        $items = $builder->get()->getResult();

        $data = ['title'=>$title,'records'=>$records,'items'=>$items];
        return view('my-request',$data);
    }

    public function Review()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1)
        {
            $title = "Review";
            //get the completed request
            $builder = $this->db->table('reviews a');
            $builder->select('b.*,c.warehouseName');
            $builder->join('request b','b.control_number=a.control_number','LEFT');
            $builder->join('tblwarehouse c','c.warehouseID=b.from','LEFT');
            $builder->WHERE('a.accountID',session()->get('loggedUser'));
            $builder->WHERE('b.status',3); //closed request
            $builder->groupBy('a.review_id');
            $records = $builder->get()->getResult();

            $data = ['title'=>$title,'records'=>$records];
            return view('review',$data);
        }
        return redirect()->back()->with('fail', 'You do not have permission to access this page!');
    }


    public function reports()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['reports']==1)
        {
            $title = "Reports";
            //get the list of ports, vessels and offices
            $builder = $this->db->table('tblwarehouse');
            $builder->select('*');
            $office = $builder->get()->getResult();

            $data = ['title'=>$title,'office'=>$office];
            return view('reports',$data);
        }
        return redirect()->back()->with('fail', 'You do not have permission to access this page!');
    }

    public function settings()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['settings']==1)
        {
            $title = "Settings";
            //get the list of ports, vessels and offices
            $builder = $this->db->table('tblwarehouse');
            $builder->select('*');
            $office = $builder->get()->getResult();
            //logs
            $builder = $this->db->table('tblsystem_logs a');
            $builder->select('a.*,b.Fullname');
            $builder->join('tblaccount b','b.accountID=a.accountID','LEFT');
            $builder->orderby('a.systemID','DESC');
            $logs = $builder->get()->getResult();

            $data = ['title'=>$title,'office'=>$office,'logs'=>$logs];
            return view('settings',$data);
        }
        return redirect()->back()->with('fail', 'You do not have permission to access this page!');
    }
}
