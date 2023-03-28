<?php
foreach($classData as $class){
$cbcs = ($class->cbcs == 'Y')?'Y':'N';
if($class->group_type == 'Group'){
    if($mode == 'REG'){
        $groupPaper = $this->db->query('select p.*,g.group_name from `group` as g join group_paper as p  on g.id=p.group_id where class_id='.$class->id.' Order by g.id,sub_group_id,p.id')->result();
        $papers =  $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$class->id,'cbcs_paper'=>$cbcs,'ce'=>"compulsory"));  
    }else{
        $papers = $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$class->id,'ce'=>"compulsory",'type'=>"theory",'cbcs_paper'=>$cbcs));
         $this->db->select('p.*,g.group_name') ;
        $this->db->from('group_paper as p');
        $this->db->join('group as g','g.id = p.group_id');
        $this->db->join('paper_master','paper_master.id = p.paper_id') ;
        $this->db->where(array('g.class_id'=>$class->id,'paper_master.type'=>"theory"));
        $groupPaper =$this->db->get()->result();	
    }
}else{
    if($mode == 'REG'){
         $papers =  $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$class->id,'cbcs_paper'=>$cbcs)); 
    }else{
        $papers =  $this->Common_model->getRecordByWhere('paper_master',array('class_id'=>$class->id,'cbcs_paper'=>$cbcs,'type'=>'theory')); 
    }  
}

if(count($papers) !=0){
    ?>
    <table class="table table-bordered">
        <tr class="text-center text-danger"><th colspan='4'><?= $this->Common_model->getCourseNameByCourseId($class->course_group_id).'('.$this->Common_model->getClassNameByClassId($class->id).')' ?></th></tr>
        <tr>
            <th style="width:50px;">S No</th>
            <th style="width:150px;">Paper Code</th>
            <?php if ($papers[0]->sub_group_id!=0): ?>
                <th style="width:250px;">Paper Type</th>
            <?php endif ?>
        
            <th style="width:550px;">Paper Name</th>
            
        </tr>
        <?php
        $sn =1;
        $ele = 1;
        foreach($papers as $paper){
            if($class->group_type == 'Paper' && $paper->ce == 'elective'){
                ?>
                <tr class="text-center text-success"><th colspan='4'>Elective Paper<?= ' '.$ele++?></th></tr>
                <?php
            }
            ?>

            <tr>
                <td style="width:50px;"><?= $sn?></td>
                <td style="width:150px;"><?= $paper->paper_code?></td>
                <?php if ($paper->sub_group_id!=0): ?>
                        <td style="width:250px;"><?php echo $this->Common_model->getSubGroupNameById($paper->sub_group_id); ?></td>
                        <?php endif ?>
                <td style="width:550px;"><?= $paper->paper_name?></td>
            </tr>
            <?php
            $sn++;
        }
        if($class->group_type == 'Group'){
            ?>
            <tr class="text-center text-success"><th colspan='4'><?= 'Select '.$class->select_group.' Subject Groups Compulsory'?></th></tr>
            <?php
            $group_name = '';
            foreach($groupPaper as $paper){
                if($group_name!=$paper->group_name){
                    $group_name=$paper->group_name;
                ?>
                <tr class="text-center text-warning"><th colspan='4'><?=$paper->group_name?></th></tr>
                <?php
                }
                ?>
                <tr>
                    <td style="width:50px;"><?= $sn?></td>
                    <td style="width:150px;"><?= $paper->paper_code?></td>
                    <?php if ($paper->sub_group_id!=0): ?>
                            <td style="width:250px;"><?php echo $this->Common_model->getSubGroupNameById($paper->sub_group_id); ?></td>
                            <?php endif ?>
                    <td style="width:550px;"><?= $paper->paper_name?></td>
                </tr>
                <?php
                $sn++;
            }
        }
        
        ?>
        
    </table>
    <?php
}
}
?>