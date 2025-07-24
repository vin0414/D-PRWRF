<?php

namespace App\Controllers;
use Dompdf\Dompdf;

class Save extends BaseController
{
    private $db;
    public function __construct()
    {
        $this->db = db_connect();
    }

    public function saveRequest()
    {
        $requestModel = new \App\Models\requestModel();
        $itemModel = new \App\Models\itemModel();
        $codeModel = new \App\Models\codeModel();
        $reviewModel = new \App\Models\reviewModel();

        $validation = $this->validate([
            'csrf_fastcat'=>['rules'=>'required','errors'=>['required'=>'Token is required. Please refresh the page']],
            'from'=>['rules'=>'required','errors'=>['required'=>'Port/Vessel is required']],
            'date_prepared'=>['rules'=>'required','errors'=>['required'=>'Date Prepared is required']],
            'date_needed'=>['rules'=>'required','errors'=>['required'=>'Date Needed is required']],
            'equipment_name'=>['rules'=>'required','errors'=>['required'=>'Equipment Name/Issue is required']],
            'model'=>['rules'=>'required','errors'=>['required'=>'Model is required']],
            'maker'=>['rules'=>'required','errors'=>['required'=>'Maker is required']],
            'brand'=>['rules'=>'required','errors'=>['required'=>'Brand is required']],
            'serial_number'=>['rules'=>'required','errors'=>['required'=>'Serial/Part Number is required']],
            'size'=>['rules'=>'required','errors'=>['required'=>'Size is required']],
            'dimension'=>['rules'=>'required','errors'=>['required'=>'Dimension is required']],
            'problem'=>['rules'=>'required','errors'=>['required'=>'Description is required']],
            'cause'=>['rules'=>'required','errors'=>['required'=>'Immediate Cause is required']],
            'work'=>['rules'=>'required','errors'=>['required'=>'Work/Action is required']],
            'approver'=>['rules'=>'required','errors'=>['required'=>'Select first approver']],
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            //compute the control number
            $listCode = $codeModel->WHERE('warehouse_id',$this->request->getPost('from'))->first();
            //count the request
            $builder = $this->db->table('request');
            $builder->select('COUNT(request_id)+1 as total');
            $builder->WHERE('year',date('Y'));
            $builder->WHERE('from',$this->request->getPost('from'));
            $code = $builder->get();
            if($row  = $code->getRow())
            {
                $code = $listCode['code']."-D/PRWRF-".date('Y')."-".str_pad($row->total, 3, '0', STR_PAD_LEFT);
            }
            
            $data = ['accountID'=>session()->get('loggedUser'),
                     'control_number'=>$code,
                     'from'=>$this->request->getPost('from'),
                     'date_prepared'=>$this->request->getPost('date_prepared'),
                     'date_needed'=>$this->request->getPost('date_needed'),
                     'equipment_name'=>$this->request->getPost('equipment_name'),
                     'equipment_model'=>$this->request->getPost('model'),
                     'equipment_maker'=>$this->request->getPost('maker'),
                     'equipment_brand'=>$this->request->getPost('brand'),
                     'serial_number'=>$this->request->getPost('serial_number'),
                     'size'=>$this->request->getPost('size'),
                     'dimension'=>$this->request->getPost('dimension'),
                     'problem'=>$this->request->getPost('problem'),
                     'cause'=>$this->request->getPost('cause'),
                     'work_required'=>$this->request->getPost('work'),
                     'status'=>0,'year'=>date('Y')];
            $requestModel->save($data);
            //get the last ID
            $requestdata = $requestModel->insertID;

            for($i = 0; $i < COUNT($this->request->getPost('description'));$i++)
            {
                $record = [
                            'request_id'=>$requestdata,
                            'account_id'=>session()->get('loggedUser'),
                            'qty'=>$this->request->getPost('qty')[$i],
                            'unit'=>$this->request->getPost('unit')[$i],
                            'description'=>$this->request->getPost('description')[$i],
                            'specification'=>$this->request->getPost('specification')[$i]
                        ];
                $itemModel->save($record);
            }
            //save to reviewModel
            $reviewData = [
                'accountID' => $this->request->getPost('approver'),
                'control_number' => $code,
                'status' => 0, // Assuming 0 is the initial status
                'remarks' => '',
            ];
            $reviewModel->save($reviewData);

            return $this->response->SetJSON(['success' => 'Successfully submitted']);
        }
    }

    public function fetchPermission()
    {
        $searchTerm = $_GET['search']['value'] ?? ''; 
        $permissionModel = new \App\Models\permissionModel();
        if ($searchTerm) {
            $permissionModel->like('rolename', $searchTerm);
        }
        $permission = $permissionModel->findAll();
        $totalRecords = $permissionModel->countAllResults();
        $permissionModel->like('rolename', $searchTerm);
        $totalFiltered = $permissionModel->countAllResults();
        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalFiltered,
            'data' => [] 
        ];
        foreach ($permission as $row) {
            $response['data'][] = [
                'role'=>$row['rolename'],
                'manage'=>($row['manage']==1) ? '<i class="bi bi-check"></i>&nbsp;Active' : '<i class="bi bi-x"></i>&nbsp;Inactive',
                'approval'=>($row['approval']==1) ? '<i class="bi bi-check"></i>&nbsp;Active' : '<i class="bi bi-x"></i>&nbsp;Inactive',
                'reports'=>($row['reports']==1) ? '<i class="bi bi-check"></i>&nbsp;Active' : '<i class="bi bi-x"></i>&nbsp;Inactive',
                'settings'=>($row['settings']==1) ? '<i class="bi bi-check"></i>&nbsp;Active' : '<i class="bi bi-x"></i>&nbsp;Inactive',
                'action' => '<button class="btn btn-success btn-sm editRole" value="' . $row['permission_id'] . '"><i class="bi bi-pencil"></i>&nbsp;Edit</button>'
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
    }

    public function saveRole()
    {
        $permissionModel = new \App\Models\permissionModel();
        $validation = $this->validate([
            'role'=>[
                    'rules'=>'required|is_unique[user_permissions.rolename]',
                    'errors'=>
                        [
                            'required'=>'Role is required',
                            'is_unique' => 'This role already exists'
                        ]
                    ],
            'csrf_fastcat'=>['rules'=>'required','errors'=>['required'=>'Token is required']]
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = [
                     'rolename'=>$this->request->getPost('role'),
                     'manage'=>$this->request->getPost('manage'),
                     'approval'=>$this->request->getPost('approval'),
                     'reports'=>$this->request->getPost('report'),
                     'settings'=>$this->request->getPost('settings')
                    ];
            $permissionModel->save($data);
            return $this->response->SetJSON(['success' => 'Successfully submitted']);
        }
    }

    public function editRole()
    {
        $permissionModel = new \App\Models\permissionModel();
        $roleId = $this->request->getGet('value');
        $roleData = $permissionModel->WHERE('permission_id',$roleId)->first();
        if ($roleData) {
            $data = [
                'role_id' => $roleData['permission_id'],
                'role' => $roleData['rolename']
            ];
            return $this->response->setJSON($data);
        } else {
            return $this->response->setJSON(['error' => 'Role not found']);
        }
    }


    public function modifyRole()
    {
        $permissionModel = new \App\Models\permissionModel();
        $validation = $this->validate([
            'edit-role'=>[
                    'rules'=>'required',
                    'errors'=>
                        [
                            'required'=>'Role is required',
                        ]
                    ],
            'csrf_fastcat'=>['rules'=>'required','errors'=>['required'=>'Token is required']]
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $id = $this->request->getPost('role_id');
            $data = [
                     'rolename'=>$this->request->getPost('edit-role'),
                     'manage'=>$this->request->getPost('edit-manage'),
                     'approval'=>$this->request->getPost('edit-approval'),
                     'reports'=>$this->request->getPost('edit-report'),
                     'settings'=>$this->request->getPost('edit-settings')
                    ];
            $permissionModel->update($id,$data);
            return $this->response->SetJSON(['success' => 'Successfully applied changes']);
        }
    }

    public function fetchCodes()
    {
        $searchTerm = $_GET['search']['value'] ?? ''; 
        $codeModel = new \App\Models\codeModel();
        $builder = $this->db->table('codes a');
        $builder->select('a.*,b.warehouseName');
        $builder->join('tblwarehouse b','b.warehouseID=a.warehouse_id','INNER');
        $builder->groupBy('a.code_id');

        if($searchTerm)
        {
            $builder->groupStart()->LIKE('a.code',$searchTerm)->orLike('b.warehouseName',$searchTerm)->groupEnd();
        }
        $codes = $builder->get()->getResult();
        $filteredRecords = count($codes);
        $totalRecords = $codeModel->countAllResults();
        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ];
        foreach ($codes as $row) {
            $response['data'][] = [
                'from' => date('F d, Y H:i a',strtotime($row->created_at)),
                'name' => $row->warehouseName,
                'code' => $row->code,
                'to' => date('F d, Y H:i a',strtotime($row->updated_at)),
                'action' =>'<button class="btn btn-success btn-sm editCode" value="' . $row->code_id . '"><i class="bi bi-pencil"></i>&nbsp;Edit</button>' 
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);
    }

    public function saveCode()
    {
        $codeModel = new \App\Models\codeModel();
        $validation = $this->validate([
            'port_vessel'=>['rules'=>'required','errors'=>['required'=>'Port/Vessel is required']],
            'code'=>[
                    'rules'=>'required|is_unique[codes.code]',
                    'errors'=>
                        [
                            'required'=>'Code is required',
                            'is_unique' => 'This code already exists'
                        ]
                    ],
        ]);
        if(!$validation)
        {
            return $this->response->SetJSON(['error' => $this->validator->getErrors()]);
        }
        else
        {
            $data = [
                     'warehouse_id'=>$this->request->getPost('port_vessel'),
                     'code'=>$this->request->getPost('code')
                    ];
            $codeModel->save($data);
            return $this->response->SetJSON(['success' => 'Successfully submitted']);
        }
    }

    public function incomingRequest()
    {
        $searchTerm = $_GET['search']['value'] ?? ''; 
        $requestModel = new \App\Models\requestModel();
        $builder = $this->db->table('request a');
        $builder->select('b.created_at,b.updated_at,a.control_number,b.remarks,b.status,b.review_id');
        $builder->join('reviews b','b.control_number=a.control_number','LEFT');
        $builder->WHERE('b.status',0); // Assuming 0 is the status for pending requests
        $builder->WHERE('b.accountID',session()->get('loggedUser'));
        $builder->groupBy('b.review_id',);

         if($searchTerm)
        {
            $builder->groupStart()->LIKE('a.control_number',$searchTerm)->groupEnd();
        }
        $result = $builder->get()->getResult();
        $filteredRecords = count($result);
        $totalRecords = $requestModel->countAllResults();
        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ];
        foreach ($result as $row) {
            $response['data'][] = [
                'date' => date('F d, Y H:i a',strtotime($row->created_at)),
                'code' => $row->control_number,
                'status' => '<span class="badge bg-warning text-white">For Review</span>',
                'remarks' => $row->remarks,
                'last_update' => date('F d, Y H:i a',strtotime($row->updated_at)),
                'action' => '<button class="btn btn-success btn-sm previewRequest" value="' . $row->review_id . '"><i class="bi bi-eye"></i>&nbsp;Review</button>'
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);  
    }

    public function processRequest()
    {
        $searchTerm = $_GET['search']['value'] ?? ''; 
        $requestModel = new \App\Models\requestModel();
        $builder = $this->db->table('request a');
        $builder->select('a.*,c.warehouseName,d.file');
        $builder->join('reviews b','b.control_number=a.control_number','LEFT');
        $builder->join('tblwarehouse c','c.warehouseID=a.from','INNER');
        $builder->join('files d','d.request_id=a.request_id','LEFT');
        $builder->WHERENOTIN('a.status',[0,3]);
        $builder->WHERE('b.accountID',session()->get('loggedUser'));
        $builder->groupBy('b.review_id',);

         if($searchTerm)
        {
            $builder->groupStart()->LIKE('a.control_number',$searchTerm)->orLIKE('c.warehouseName',$searchTerm)->groupEnd();
        }
        $result = $builder->get()->getResult();
        $filteredRecords = count($result);
        $totalRecords = $requestModel->countAllResults();
        $response = [
            "draw" => $_GET['draw'],
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            'data' => [] 
        ]; 
        foreach ($result as $row) {
            $response['data'][] = [
                'code' => $row->control_number,
                'from' => $row->warehouseName,
                'equipment' => $row->equipment_name,
                'problem' => $row->problem,
                'cause' => $row->cause,
                'assign' => $row->assigned_to,
                'status' => ($row->status == 1) ? '<span class="badge bg-info text-white">Reviewed</span>' :
                            (($row->status == 2) ? '<span class="badge bg-danger text-white">Declined</span>' : 
                            (($row->status == 4) ? '<span class="badge bg-primary text-white">Accepted</span>' : 
                            (($row->status == 5) ? '<span class="badge bg-success text-white">Verified</span>' : 
                            '<span class="badge bg-primary text-white">Accepted</span>'))),
                'action' => '<div class="dropdown">
                                <a class="btn btn-primary btn-sm font-24 line-height-1 dropdown-toggle"
                                    href="#" role="button" data-toggle="dropdown">
                                   More
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                    <button type="button" class="dropdown-item viewRequest" value="'.$row->request_id.'"
                                        ><i class="dw dw-eye"></i> View Details
                                    </button>'
                                    . ($row->status != 2 ? '
                                    <button type="button" class="dropdown-item edit" value="' . $row->request_id . '">
                                        <i class="dw dw-edit2"></i> Edit Status
                                    </button>' : '') . '
                                    <a href="'.base_url('files/').$row->file.'" class="dropdown-item" target="_BLANK">
                                        <i class="dw dw-attachment"></i> Attachment
                                    </a>
                                </div>
                            </div>'
            ];
        }
        // Return the response as JSON
        return $this->response->setJSON($response);  
    }

    public function fetchRequest()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1){
            $val = $this->request->getGet('value');
            $requestModel = new \App\Models\requestModel();
            $data = $requestModel->WHERE('request_id',$val)->first();
            if ($data) {
                $warehouseModel = new \App\Models\warehouseModel();
                $warehouse = $warehouseModel->WHERE('warehouseID',$data['from'])->first();
                $output="";
                $output.='<div class="card mb-3">
                                <div class="card-header" data-toggle="collapse" data-target="#faq1-1" style="cursor: pointer;">
                                    <i class="icon-copy dw dw-search"></i>&nbsp;Details
                                </div>
                                <div id="faq1-1" class="collapse show">';
                $output .= '<div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-12 form-group">
                                        <div class="row g-3">
                                            <div class="col-lg-6">
                                                <label for="control_number" class="form-label">Control Number</label>
                                                <input type="text" class="form-control" id="control_number" value="'.$data['control_number'].'" readonly>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="from" class="form-label">Port/Vessel</label>
                                                <input type="text" class="form-control" id="from" value="'.$warehouse['warehouseName'].'" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>Equipment Name/Issue</label>
                                        <input type="text" class="form-control" value="'.$data['equipment_name'].'" readonly/>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>I. Equipment Details</label>
                                        <textarea class="form-control" rows="3" readonly>'.$data['equipment_model'].' / '.$data['equipment_maker'].' / '.$data['equipment_brand'].' / '.$data['serial_number'].' / '.$data['size'].' / '.$data['dimension'].'</textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <label>II. Equipment Defect/Problem</label>
                                        <div class="row g-3">
                                            <div class="col-lg-6 form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" rows="3" readonly>'.$data['problem'].'</textarea>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Immediate Cause</label>
                                                <textarea class="form-control" rows="3" readonly>'.$data['cause'].'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>III. Work Required</label>
                                        <textarea class="form-control" rows="3" readonly>'.$data['work_required'].'</textarea>
                                    </div>
                                </div>
                            </div>';
                $output.='</div></div>';
                //items
                $output .= '<div class="card mb-3">
                                <div class="card-header collapsed" data-toggle="collapse" data-target="#faq1-2" style="cursor: pointer;">
                                    <i class="icon-copy dw dw-list"></i>&nbsp;Items
                                </div>
                                <div id="faq1-2" class="collapse">';   
                $output .= '<div class="card-body">
                                <div class="row g-3">';   
                $itemModel = new \App\Models\itemModel();
                $items = $itemModel->WHERE('request_id',$data['request_id'])->findAll();
                if($items)
                {
                    foreach($items as $item)
                    {
                        $output .= '<div class="col-lg-12 form-group">
                                        <div class="row g-3">
                                            <div class="col-lg-2">
                                                <label>Qty</label>
                                                <input type="text" class="form-control" value="'.$item['qty'].'" readonly>
                                            </div>
                                            <div class="col-lg-2">
                                                <label>Unit</label>
                                                <input type="text" class="form-control" value="'.$item['unit'].'" readonly>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>Description</label>
                                                <input type="text" class="form-control" value="'.$item['description'].'" readonly>
                                            </div>
                                        </div>
                                    </div>';
                    }
                }
                else
                {
                    $output .= '<p>No items found for this request.</p>';
                }   
                $output .= '</div></div>';
                $output .= '</div></div>';
                //review
                $reviewModel = new \App\Models\reviewModel();
                $review = $reviewModel->WHERE('control_number',$data['control_number'])->first();
                if($review['status']==0):
                $output.='<div class="row">';
                $output.='<div class="col-lg-12 form-group">
                            <label>Work Required Assigned To</label>
                            <select class="form-control" name="assigned_to" id="assigned_to">
                                <option value="">Choose</option>
                                <option>Crew</option>
                                <option>Technical/Engineer Personnel</option>
                                <option>Service Provider</option>
                            </select>
                          </div>';
                $output.='<div class="col-lg-12 form-group">
                            <button type="button" class="btn btn-primary btn-sm accept" value="'.$review['review_id'].'"><i class="icon-copy dw dw-check"></i>&nbsp;Accept</button>
                            <button type="button" class="btn btn-danger btn-sm reject" value="'.$review['review_id'].'"><i class="icon-copy dw dw-cancel"></i>&nbsp;Cancel</button>
                          </div>';
                $output.='</div>';
                endif;
                echo $output;
            } else {
                return $this->response->setJSON(['error' => 'Request not found']);
            }
        }
        else
        {
            return $this->response->setJSON(['error' => 'You do not have permission to approve requests']);
        }
    }

    public function acceptRequest()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1){
            $reviewModel = new \App\Models\reviewModel();
            $requestModel = new \App\Models\requestModel();
            $systemLogsModel = new \App\Models\systemLogsModel();
            $val = $this->request->getPost('value');
            $assign = $this->request->getPost('assign');
            if((empty($val) || !is_numeric($val)) || empty($assign))
            {
                echo "Invalid! Please try again";
            }
            else
            {
                //get the control number
                $review = $reviewModel->WHERE('review_id',$val)->first();
                //get the primary id of the request
                $record = $requestModel->WHERE('control_number',$review['control_number'])->first();
                //update the status of the request and review
                $data = ['status'=>1,'assigned_to'=>$assign];
                $requestModel->update($record['request_id'],$data);
                $reviewModel->update($val,$data);
                //logs
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Accept D/PRWRF : '.$review['control_number']];
                $systemLogsModel->save($values);
                echo "success";
            }
        }
        else
        {
            echo "You do not have permission to approve requests";
        }
    }

    public function rejectRequest()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1){
            $reviewModel = new \App\Models\reviewModel();
            $requestModel = new \App\Models\requestModel();
            $systemLogsModel = new \App\Models\systemLogsModel();
            $val = $this->request->getPost('value');
            if(empty($val) || !is_numeric($val))
            {
                echo "Invalid Request";
            }
            else
            {
                //get the control number
                $review = $reviewModel->WHERE('review_id',$val)->first();
                //get the primary id of the request
                $record = $requestModel->WHERE('control_number',$review['control_number'])->first();
                //update the status of the request and review
                $data = ['status'=>2];
                $requestModel->update($record['request_id'],$data);
                $reviewModel->update($val,$data);
                //logs
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Reject D/PRWRF : '.$review['control_number']];
                $systemLogsModel->save($values);
                echo "success";
            }
        }
        else
        {
            echo "You do not have permission to reject requests";
        }
    }

    public function viewRequest()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1)
        {
            $val = $this->request->getGet('value');
            $requestModel = new \App\Models\requestModel();
            $data = $requestModel->WHERE('request_id',$val)->first();
            if ($data) {
                $warehouseModel = new \App\Models\warehouseModel();
                $warehouse = $warehouseModel->WHERE('warehouseID',$data['from'])->first();
                $output="";
                $output.='<div class="card mb-3">
                                <div class="card-header" data-toggle="collapse" data-target="#faq1-1" style="cursor: pointer;">
                                    <i class="icon-copy dw dw-search"></i>&nbsp;Details
                                </div>
                                <div id="faq1-1" class="collapse show">';
                $output .= '<div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-12 form-group">
                                        <div class="row g-3">
                                            <div class="col-lg-6">
                                                <label for="control_number" class="form-label">Control Number</label>
                                                <input type="text" class="form-control" id="control_number" value="'.$data['control_number'].'" readonly>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="from" class="form-label">Port/Vessel</label>
                                                <input type="text" class="form-control" id="from" value="'.$warehouse['warehouseName'].'" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>Equipment Name/Issue</label>
                                        <input type="text" class="form-control" value="'.$data['equipment_name'].'" readonly/>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>I. Equipment Details</label>
                                        <textarea class="form-control" rows="3" readonly>'.$data['equipment_model'].' / '.$data['equipment_maker'].' / '.$data['equipment_brand'].' / '.$data['serial_number'].' / '.$data['size'].' / '.$data['dimension'].'</textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <label>II. Equipment Defect/Problem</label>
                                        <div class="row g-3">
                                            <div class="col-lg-6 form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" rows="3" readonly>'.$data['problem'].'</textarea>
                                            </div>
                                            <div class="col-lg-6 form-group">
                                                <label>Immediate Cause</label>
                                                <textarea class="form-control" rows="3" readonly>'.$data['cause'].'</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                        <label>III. Work Required</label>
                                        <textarea class="form-control" rows="3" readonly>'.$data['work_required'].'</textarea>
                                    </div>
                                </div>
                            </div>';
                $output.='</div></div>';
                //items
                $output .= '<div class="card mb-3">
                                <div class="card-header collapsed" data-toggle="collapse" data-target="#faq1-2" style="cursor: pointer;">
                                    <i class="icon-copy dw dw-list"></i>&nbsp;Items
                                </div>
                                <div id="faq1-2" class="collapse">';   
                $output .= '<div class="card-body">
                                <div class="row g-3">';   
                $itemModel = new \App\Models\itemModel();
                $items = $itemModel->WHERE('request_id',$data['request_id'])->findAll();
                if($items)
                {
                    foreach($items as $item)
                    {
                        $output .= '<div class="col-lg-12 form-group">
                                        <div class="row g-3">
                                            <div class="col-lg-2">
                                                <label>Qty</label>
                                                <input type="text" class="form-control" value="'.$item['qty'].'" readonly>
                                            </div>
                                            <div class="col-lg-2">
                                                <label>Unit</label>
                                                <input type="text" class="form-control" value="'.$item['unit'].'" readonly>
                                            </div>
                                            <div class="col-lg-8">
                                                <label>Description</label>
                                                <input type="text" class="form-control" value="'.$item['description'].'" readonly>
                                            </div>
                                        </div>
                                    </div>';
                    }
                }
                else
                {
                    $output .= '<p>No items found for this request.</p>';
                }   
                $output .= '</div></div>';
                $output .= '</div></div>';
                echo $output;
            }
            else
            {
                return $this->response->setJSON(['error' => 'Request not found']);
            }
        }
        else
        {
            echo "You do not have permission to view requests";
        }
    }

    public function editStatus()
    {
        $permissionModel = new \App\Models\permissionModel();
        $role = $permissionModel->WHERE('rolename',session()->get('role'))->first(); 
        if($role['approval']==1)
        {
            $requestModel = new \App\Models\requestModel();
            $systemLogsModel = new \App\Models\systemLogsModel();
            $val = $this->request->getPost('request_id');
            $status = $this->request->getPost('status');
            if(empty($val) || !is_numeric($val))
            {
                echo "Invalid Request";
            }
            else
            {

                $record = $requestModel->WHERE('request_id',$val)->first();
                //update the status of the request and review
                $data = ['status'=>$status];
                $requestModel->update($record['request_id'],$data);
                //logs
                $values = ['accountID'=>session()->get('loggedUser'),'Date'=>date('Y-m-d H:i:s a'),'Activity'=>'Modify the status of D/PRWRF : '.$record['control_number']];
                $systemLogsModel->save($values);
                echo "success";
            }
        }
        else
        {
            echo "You do not have permission to edit status";
        }
    }

    public function uploadFile()
    {
        $uploadModel = new \App\Models\uploadFileModel();
        $validation = $this->validate([
            'csrf_fastcat'=>'required',
            'request_id'=>[
                'rules'=>'required|is_unique[files.request_id]',
                'errors'=>[
                    'required'=>'Please select any control number',
                    'is_unique'=>'Already submitted the attachment'
                ]
            ],
            'file'=>[
                'rules' => 'uploaded[file]|mime_in[file,application/zip,application/x-zip-compressed,application/pdf]|max_size[file,25600]',
                'errors' => [
                    'uploaded' => 'You must choose a file to upload.',
                    'mime_in' => 'The file must be a PDF or ZIP file.',
                    'max_size' => 'The file size must not exceed 25MB.',
                ]
            ]
        ]);
        if(!$validation) {
            return $this->response->setJSON(['error' => $this->validator->getErrors()]);
        }

        $file = $this->request->getFile('file');
        if (!$file->isValid()) {
            return $this->response->setJSON(['error' => 'Invalid file upload.']);
        }

        // Use a unique name to avoid collisions
        $originalName = uniqid() . '_' . $file->getClientName();

        // Ensure the directory exists
        if (!is_dir('files/')) {
            mkdir('files/', 0755, true);
        }

        if (!$file->move('files/', $originalName)) {
            return $this->response->setJSON(['error' => 'Failed to move uploaded file.']);
        }

        $data = [
            'request_id'=>$this->request->getPost('request_id'),
            'accountID'=>session()->get('loggedUser'),
            'file'=>$originalName
        ];
        $uploadModel->save($data);

        return $this->response->setJSON(['success' => 'Successfully uploaded']);
    }

    public function fetchReport()
    {
        $from = $this->request->getGet('from');
        if(empty($from)|| !is_numeric($from))
        {
            echo "<tr><td colspan='5' class='text-center'>No Record(s) found</td></tr>";
        }
        else
        {
            $output="";
            $requestModel = new \App\Models\requestModel();
            $records = $requestModel->WHERE('from',$from)->findAll();
            foreach($records as $row)
            {
                $output.='<tr>
                            <td>'.$row['control_number'].'</td>
                            <td>'.$row['equipment_name'].'</td>
                            <td>'.$row['problem'].'</td>
                            <td>'.$row['cause'].'</td>';
                            if($row['status']==0):
                $output.='<td><span class="badge bg-warning text-white">For Review</span></td>';
                            elseif($row['status']==1):
                $output.='<td><span class="badge bg-info text-white">Reviewed</span></td>';
                            elseif($row['status']==2):
                $output.='<td><span class="badge bg-danger text-white">Declined</span></td>';
                            elseif($row['status']==3):
                $output.='<td><span class="badge bg-secondary text-white">Close</span></td>';
                            elseif($row['status']==4):
                $output.='<td><span class="badge bg-primary text-white">Accepted</span></td>';
                            elseif($row['status']==5):
                $output.='<td><span class="badge bg-success text-white">Verified</span></td>';
                            endif;
                $output.='</tr>';
            }
            echo $output;
        }
    }

    public function print($id)
    {
        $dompdf = new Dompdf();
        if(empty($id)||!is_numeric($id))
        {
            echo "Invalid! Please try again";
        }
        else
        {
            $requestModel = new \App\Models\requestModel();
            $record = $requestModel->find($id);
            if(empty($record))
            {
                echo "No Record(s) found";
            }
            else
            {
                $template = 'Hi';
                $dompdf->loadHtml($template);
                $dompdf->setPaper('Letter', 'portrait');
                $dompdf->render();
                $dompdf->stream($record['control_number'].".pdf");
                exit();
            }
        }
    }
}