require([
        "dojox/charting/Chart",
        "dojox/charting/plot2d/Columns",
        "dojox/charting/themes/MiamiNice",
        // Require the highlighter
        "dojox/charting/action2d/Highlight",
        "dojox/charting/axis2d/Default",
        //    We want to use Markers
        "dojox/charting/plot2d/Markers",
        "dojo/domReady!"
    ],
    function (Chart, Colunms, theme, Highlight) {

        // Define the data
        var chartData = [3, 2, 2];

        var chart = new Chart("column_chart");
        chart.addPlot("default", {type: "Columns", gap: 12});
        chart.addAxis("x", {
            labels: [
                {value: 1, text: "Jan - Apr"}, {value: 2, text: "May - Aug"},
                {value: 3, text: "Sep - Dec"}]
        });
        chart.addAxis("y", {vertical: true, leftBottom: true, min: 1});
        chart.addSeries("Series 1", chartData,
            {plot: "default", stroke: {color: "#466C89", width: 1}, fill: "#FF9900"});
        // Highlight!
        new Highlight(chart,"default");

        chart.setTheme(theme);
        chart.render();
    });