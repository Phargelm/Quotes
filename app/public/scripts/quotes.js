$(function() {

    $(".date").datepicker({
        dateFormat: "yy-mm-dd",
        changeYear: true,
        changeMonth: true
    });

    let startDate = $("#start-date").datepicker().on("change", function() {
        endDate.datepicker("option", "minDate", getDate(this));
    });

    let endDate = $("#end-date").datepicker().on("change", function() {
        startDate.datepicker("option", "maxDate", getDate(this));
    });

    function getDate(element) {
        try {
            return $.datepicker.parseDate("yy-mm-dd", element.value);
        } catch(error) {
            return null;
        }
    };

    let datatable = $("#quotes-table").DataTable({
        columns: [
            {data: 'Date'}, {data: 'Open'},
            {data: 'High'}, {data: 'Low'},
            {data: 'Close'}, {data: 'Volume'}
        ]
    });

    let chart = new CanvasJS.Chart('chart-container', {
        animationEnabled: true,
        title: {
            text: "Stock Price"
        },
        axisX: {
            valueFormatString: "DD MM YYYY",
            crosshair: {
                enabled: true,
                snapToDataPoint: true
            }
        },
        axisY: {
            title: "Price",
            includeZero: false,
            valueFormatString: "$##0.00",
            crosshair: {
                enabled: true,
                snapToDataPoint: true,
                labelFormatter: function(e) {
                    return "$" + CanvasJS.formatNumber(e.value, "##0.00");
                }
            }
        },
        data: [{
            type: "line",
            name: "Adg. Close",
		    showInLegend: true,
            xValueFormatString: "DD MM YYYY",
            yValueFormatString: "$##0.00",
            dataPoints: []
        }, {
            type: "line",
            name: "Adj. Open",
            showInLegend: true,
            xValueFormatString: "DD MM YYYY",
            yValueFormatString: "$##0.00",
            dataPoints: []
        }]
    });
    chart.render();

    $("#quotes-form").on("submit", function(event) {
        event.preventDefault();

        validateInputs($('input[data-validation]')).pipe(function() {
            $("button[type='submit']").prop('disabled', true);
            return $.ajax({url: "/quotes", data: $(event.target).serialize()});
        }).done(function(data) {
            if (data.length == 0) {
                alert('No quotes found');
            }
            $("button[type='submit']").prop('disabled', false);
            datatable.clear();
            datatable.rows.add(data);
            datatable.columns.adjust().draw();
            chart.options.title.text = 'Stock Price of ' + $('#company-symbol').val();
            chart.options.data[0].dataPoints = [];
            chart.options.data[1].dataPoints = [];
            for (quotation of data) {
                chart.options.data[0].dataPoints.push({
                    x: new Date(Date.parse(quotation['Date'])),
                    y: parseFloat(quotation['Adj. Close'])
                });
                chart.options.data[1].dataPoints.push({
                    x: new Date(Date.parse(quotation['Date'])),
                    y: parseFloat(quotation['Adj. Open'])
                });
            }
            chart.render();
        }).fail(function(jqXHR) {
            
            let response = jqXHR.responseJSON;
            
            if (response.status != "validation-error") {
                alert("Server error is occured!:(");
                return;
            }

            for (let name in response.errors) {
                let inputErrors = response.errors[name];
                let inputs = $("input[name='" + name + "']");
                inputs.addClass('is-invalid');
                $("button[type='submit']").prop('disabled', true);
                for (let errorMessage of inputErrors) {
                    inputs.after("<div class=\"invalid-feedback\">" + errorMessage + "</div>");
                }
            }
        });

    });
});