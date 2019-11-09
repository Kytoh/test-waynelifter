$().ready(function () {
    $('.saveConfig').click(function (event) {
        event.preventDefault();

        var steps = [];
        $(".steps").each(function (index, data) {
            steps.push({
                id: $(data).find('.stepId').val(),
                minuteInterval: $(data).find('.minuteInterval').val(),
                startTime: $(data).find('.startTime').val(),
                endTime: $(data).find('.endTime').val(),
                startFloor: $(data).find('.startFloor').val(),
                endFloor: $(data).find('.endFloor').val()
            })
        });
        sendConfig(steps);
    });

    $('.deleteConfig').click(function (event) {
        event.preventDefault();
        deleteConfig($(this).data('id'));
    });

    $('.newLine').click(function(event){
        event.preventDefault();
        $('.prependHere').before('<div class="col-md-12 row steps newStep"><div class="col-md-1 form-group">\n' +
            '                        <input type="number" class="form-control stepId" disabled value="">\n' +
            '                    </div>\n' +
            '                    <div class="col-md-2 form-group">\n' +
            '                        <input type="number" class="form-control minuteInterval" value="">\n' +
            '                    </div>\n' +
            '                    <div class="col-md-2 form-group">\n' +
            '                        <input type="time" class="form-control startTime" value="">\n' +
            '                    </div>\n' +
            '                    <div class="col-md-2 form-group">\n' +
            '                        <input type="time" class="form-control endTime" value="">\n' +
            '                    </div>\n' +
            '                    <div class="col-md-2 form-group">\n' +
            '                        <input type="text" class="form-control startFloor" value="">\n' +
            '                    </div>\n' +
            '                    <div class="col-md-2 form-group">\n' +
            '                        <input type="text" class="form-control endFloor" value="">\n' +
            '                    </div></div>');
    });
});

function sendConfig(steps) {
    axios.post('/config', {
        liftCount: $("*[name=liftCount]").val(),
        floorCount: $("*[name=floorCount]").val(),
        steps: steps
    })
        .then(function (response) {
            window.location.reload()
        })
        .catch(function (error) {
            console.log(error);
        });
}
function deleteConfig(StepId) {
    axios.delete('/config', {
    data:{ id: StepId }
    })
        .then(function (response) {
            window.location.reload()
        })
        .catch(function (error) {
            console.log(error);
        });
}
