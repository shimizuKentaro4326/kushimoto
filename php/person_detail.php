<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/common.php");
?>
<script type="text/javascript" src="/kushimoto/js/jquery.min.js"></script>
<script type="text/javascript">
var householder_list;
var household_id;
var param =
<?php
    $param=array();
    if (isset($_GET['people_id'])){
        $param=getOneRecord("
        select    
        t1.id as people_id,
        t1.last_name_rubi as last_name_rubi,
        t1.first_name_rubi as first_name_rubi,
        t1.last_name as last_name,
        t1.first_name as first_name,
        t1.sex as sex,
        t1.is_householder as is_householder,
        t3.id as postal_code,
        t2.street as street,
        if(t1.is_householder, t1.household_id, null) as household_id_orig,
        if(t1.is_householder, null, t1.household_id) as household_id_ref,
        t2.tel as tel,
        t1.celphone as celphone,
        t1.email as email,
        t1.birthyear as birthyear,
        t1.birthmonth as birthmonth,
        t1.birthday as birthday,
        t1.party_id as party_id,
        t1.deadday as deadday,
        t1.memo as memo
        from
        people t1
        left outer join household t2 on t1.household_id = t2.id
        left outer join postal_code t3 on t2.postal_code_id = t3.id
        where
        t1.id = '" . $_GET['people_id'] . "'"
        );

        $param+=getMultiRecords("demand","
        select    
        t2.acceptance_date as acceptance_date,
        t3.name as type,
        t4.value as state,
        t2.title as title,
        t2.contents as contents,
        t5.correspondence_date as correspondence_date,
        t5.contents as correspondence_contents
        from
        people t1
        left outer join demand t2 on t2.people_id = t1.id
        left outer join demand_type t3 on t3.id = t2.demand_type_id
        left outer join state t4 on t4.id = t2.state_id
        left outer join correspondence t5 on t5.demand_id = t2.id 
        where
        t1.id = '" . $_GET['people_id'] . "'
        order by
        t2.acceptance_date asc"
        );
    }
    echo json_encode($param);
?>;

var masterData = 
<?php
    $masterData = array();
    //
    $masterData=getMultiRecords("postal_code","
    select    
    t1.id as id,
    t1.address as value
    from
    postal_code t1
    ");
    //
    $masterData+=getMultiRecords("party_id","
    select    
    t1.id as id,
    t1.name as value
    from
    party t1
    ");
    $masterData+=getMultiRecords("household_id_ref","
    select    
    t2.id as id,
    concat(t1.last_name, t1.first_name) as value
    from
    people t1
    left outer join household t2 on t1.household_id = t2.id
    left outer join postal_code t3 on t2.postal_code_id = t3.id
    left join household t4 on t4.postal_code_id = t3.id
    inner join people t5 on t5.household_id = t4.id  
    where
    t5.is_householder is true
    and t1.id = '" . $_GET['people_id'] . "'"
    );
    echo json_encode($masterData);
?>;
//init
$(function(){
    //masterData
    $.each(masterData, function(i,val){
        element=$('*[name="' + i + '"]');
        switch(element.prop("tagName")){
            case "SELECT" :
            $.each(val, function(j,val2){
                $option = $('<option>').val(val2['id']).text(val2['value']);
                element.append($option);
            });
            break;
        }
    });
    //param 
    $.each(param, function(i, val){
        element=$('*[name="' + i + '"]');
        switch(element.prop("tagName")){
            case "INPUT" :
            switch(element.prop("type")){
                case "text":
                element.val(val);
                break;
                case "hidden":
                element.val(val);
                break;
                case "radio":
                element.each(function(){
                    if($(this).val() == val){
                        $(this).prop('checked', true);
                    }
                })
                break;

            }
            break;
            case "SELECT" :
            element.val(val);
            break;
            case "TEXTAREA" :
            element.val(val);
            break;
            case "TABLE" :
            var tr ="<tr>";
            $.each(val,function(j,val2){
                element.children('thead').children('tr').children().each(function(){
                    tr+="<td>" + val2[$(this).attr("name")] + "</td>"
                })
            })
            tr+="</tr>"
            element.append(tr);
            break;
        }
    });

    //世帯主ラジオボタン変更
    $('*[name="is_householder"]').change(function(){
        var selected = $('*[name="is_householder"]:checked').val(); 
        if(selected == '1'){
            $("#householder").hide();
            $('[name="tel"]').prop("disabled", false);
            $('[name="street"]').prop("disabled", false);
        }else{
            $("#householder").show();
            $('[name="tel"]').prop("disabled", true);
            $('[name="street"]').prop("disabled", true);
            $('*[name="postal_code"]').trigger("change");
        }
    }); 

    //非世帯主時、住所変更
    $('*[name="postal_code"]').change(function(){
        if($("#is_householder_false").is(":checked")){
            $postal_code = $(this).val();
            $household_id = $('*[name="household_id_ref"]').val();
            $.ajax("/kushimoto/php/getHHerlist.php",
            {
                type:"POST",
            data:{postal_code:$postal_code}
            }
            ).done(function(data, status, jqxhr){
                res = $.parseJSON(data);
                householder_list = res['householder_list'];
                $('*[name="household_id_ref"]').empty();
                $.each(householder_list, function(i,val){
                    $option = $('<option>').val(i).text(val['name']);
                    $('*[name="household_id_ref"]').append($option);
                });   
                $('*[name="household_id_ref"]').val($household_id);
                $('*[name="household_id_ref"]').trigger("change");
            });
        }
    }); 
    //非世帯主時、世帯主変更
    $('*[name="household_id_ref"]').change(function(){
        if($("#is_householder_false").is(":checked")){
            var household_id = $(this).val();
            $('*[name="street"]').val(householder_list[household_id]['street']);
            $('*[name="tel"]').val(householder_list[household_id]['tel']);
        };
    }); 


    $('*[name="is_householder"]').trigger("change");
    $('*[name="postal_code"]').trigger("change");
});
</script>
