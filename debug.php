<div class="form-group">
                            <label for="date">Päivämäärä</label>
                            <input type="date" class="form-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>">
                        </div>
<div class="form-group col">
                                <label for="starttime">Aloitusaika</label>
                                <input type="datetime-local" class="form-control" name="starttime" id="starttime" value="<?php echo date("Y-m-d") . "T00:00"; ?>">
                            </div>
                            <div class="form-group col">
                                <label for="endtime">Lopetusaika</label>
                                <input type="datetime-local" class="form-control" name="endtime" value="<?php echo date("Y-m-d\TH:i"); ?>">
                            </div>
                            <div class="form-group col">
                                <label for="break">Tauko</label>
                                <input type="time" class="form-control" name="breaktime">
                            </div>
                            <script src="scripts/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        var custom_start_time = "start";
        var custom_end_time = "end";
        var break_time = "break";
        var string = ("<b>Aloitus: </b>" + custom_start_time + "<br>" +
                        "<b>Lopetus: </b>" + custom_end_time + "<br>" +
                        "<b>Tauko: </b> " + break_time + "mi");
        alert(string);
    });
</script>