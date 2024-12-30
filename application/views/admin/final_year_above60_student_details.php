<div style="text-align:center;" >

    <table width="100%" border="1" align="center"  cellpadding="5" >
        <thead>
            <tr>
                <th rowspan="3">#</th>
                <th rowspan="3">Course Name</th> 
                <th rowspan="3">Class Name</th> 
                <th rowspan="3">Category</th>
                <th colspan="15">Number Of Total Student</th>
            </tr>
            <tr>
                <th colspan="3">General</th>
                <th colspan="3">OBC</th>
                <th colspan="3">SC</th> 
                <th colspan="3">ST</th>
                <th colspan="3">Total</th>
            </tr>
            <tr>
                <?php for ($i = 0; $i < 5; $i++) { ?>
                <th>Total</th>
                <th>Girls</th>
                <th>Boys</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="4">1</td>
                <td rowspan="4"><?= $students->course_name;?></td>
                <td rowspan="4"><?= $students->class_name;?></td>
                <td>Total</td>
                <td><?= $students->std_general?></td>
                <td><?= $students->general_female?></td>
                <td><?= $students->general_male?></td>
                <td><?= $students->std_obc?></td>
                <td><?= $students->obc_female?></td>
                <td><?= $students->obc_male?></td>
                <td><?= $students->std_sc?></td>
                <td><?= $students->sc_female?></td>
                <td><?= $students->sc_male?></td>
                <td><?= $students->std_st?></td>
                <td><?= $students->st_female?></td>
                <td><?= $students->st_male?></td>
                <td><?= $students->std_total?></td>
                <td><?= $students->male?></td>
                <td><?= $students->female?></td>
            </tr>
            <tr>
                <td>PWD(out of Total)</td>
                <td><?= $students->handicapped_general?></td>
                <td><?= $students->handicapped_general_female?></td>
                <td><?= $students->handicapped_general_male?></td>
                <td><?= $students->handicapped_obc?></td>
                <td><?= $students->handicapped_obc_female?></td>
                <td><?= $students->handicapped_obc_male?></td>
                <td><?= $students->handicapped_sc?></td>
                <td><?= $students->handicapped_sc_female?></td>
                <td><?= $students->handicapped_sc_male?></td>
                <td><?= $students->handicapped_st?></td>
                <td><?= $students->handicapped_st_female?></td>
                <td><?= $students->handicapped_st_male?></td>
                <td><?= $students->handicapped_total?></td>
                <td><?= $students->handicapped_male?></td>
                <td><?= $students->handicapped_female?></td>
            </tr>
            <tr>
                <td>Muslim Minority(out of Total)</td>
                <td><?= $students->minority_general?></td>
                <td><?= $students->minority_general_female?></td>
                <td><?= $students->minority_general_male?></td>
                <td><?= $students->minority_obc?></td>
                <td><?= $students->minority_obc_female?></td>
                <td><?= $students->minority_obc_male?></td>
                <td><?= $students->minority_sc?></td>
                <td><?= $students->minority_sc_female?></td>
                <td><?= $students->minority_sc_male?></td>
                <td><?= $students->minority_st?></td>
                <td><?= $students->minority_st_female?></td>
                <td><?= $students->minority_st_male?></td>
                <td><?= $students->minority_total?></td>
                <td><?= $students->minority_male?></td>
                <td><?= $students->minority_female?></td>
            </tr>
            <tr>
                <td>Other Minority(out of Total)</td>
                <td><?= $students->other_minority_general?></td>
                <td><?= $students->other_minority_general_female?></td>
                <td><?= $students->other_minority_general_male?></td>
                <td><?= $students->other_minority_obc?></td>
                <td><?= $students->other_minority_obc_female?></td>
                <td><?= $students->other_minority_obc_male?></td>
                <td><?= $students->other_minority_sc?></td>
                <td><?= $students->other_minority_sc_female?></td>
                <td><?= $students->other_minority_sc_male?></td>
                <td><?= $students->other_minority_st?></td>
                <td><?= $students->other_minority_st_female?></td>
                <td><?= $students->other_minority_st_male?></td>
                <td><?= $students->other_minority_total?></td>
                <td><?= $students->other_minority_male?></td>
                <td><?= $students->other_minority_female?></td>
            </tr>
           
           
        </tbody>
    </table>
</div>