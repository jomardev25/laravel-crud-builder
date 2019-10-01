<td>
    <input type="text" class="form-control required" name="field_name[]" id="field_name"/>
</td>
<td>
    <select class="form-control ls-select2 required" id="db_type" name="db_type[]">
        <option value="bigIncrements" selected>Big Increments</option>
        <option value="bigInteger">Big Integer</option>
        <option value="binary">Binary</option>
        <option value="boolean">Boolean</option>
        <option value="char">Char</option>
        <option value="date">Date</option>
        <option value="dateTime">DateTime</option>
        <option value="dateTimeTz">DateTimeTz</option>
        <option value="decimal">Decimal</option>
    </select>
</td>
<td>
    <input type="text" class="form-control" name="length[]"/>
</td>
<td>
    <input type="text" class="form-control" name="decimals[]"/>
<td>
    <select class="form-control ls-select2 required" name="default[]">
        <option value="None" selected>None</option>
        <option value="User_Defined">As defined</option>
        <option value="Null">Null</option>
        <option value="Curren_Timestamp">Curren_Timestamp</option>
    </select>
    <input type="text" name="user_defined[]" class="form-control mt-2 d-none" value="None"/>
</td>
<td class="text-right">
    <input type="checkbox" value="1" class="form-check-input" name="not_null[]"/>
</td>
<td>
    <select class="form-control ls-select2" name="index[]">
        <option value="none" selected>None</option>
        <option value="primary">Primary</option>
        <option value="unique">Unique</option>
        <option value="index">Index</option>
        <option value="spatial">Spatial</option>
    </select>
</td>
<td class="text-right">
    <i class="icon-fa icon-fa-trash"></i>
</td>
