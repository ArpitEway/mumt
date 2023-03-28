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
        <tr class="text-center text-danger"><th colspan='4'><?= $this->Common_model->getClassNameByClassId($class->id) ?></th></tr>
        <tr>
            <th class="w-10">S No</th>
            <th class="w-25">Paper Code</th>
            <?php if ($papers[0]->sub_group_id!=0): ?>
                <th class="w-25">Paper Type</th>
            <?php endif ?>
        
            <th class="w-40">Paper Name</th>
            
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
                <td class="w-10"><?= $sn?></td>
                <td class="w-25"><?= $paper->paper_code?></td>
                <?php if ($paper->sub_group_id!=0): ?>
                        <td class="w-25"><?php echo $this->Common_model->getSubGroupNameById($paper->sub_group_id); ?></td>
                        <?php endif ?>
                <td class="w-40"><?= $paper->paper_name?></td>
            </tr>
            <?php
            $sn++;
        }
        if($class->group_type == 'Group'){
        
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
                    <td class="w-10"><?= $sn?></td>
                    <td class="w-25"><?= $paper->paper_code?></td>
                    <?php if ($paper->sub_group_id!=0): ?>
                            <td class="w-25"><?php echo $this->Common_model->getSubGroupNameById($paper->sub_group_id); ?></td>
                            <?php endif ?>
                    <td class="w-40"><?= $paper->paper_name?></td>
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