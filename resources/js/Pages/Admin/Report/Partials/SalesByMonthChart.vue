<script setup>
import * as d3 from "d3";
import {onMounted, onUnmounted} from "vue";
import {formatCurrency} from "@/Utils/currency";

const { sales_by_month } = defineProps({
    sales_by_month: {
        type: Array,
        required: true
    }
});

function generateSalesByMonthChart() {
    const svg = d3.select("#salesByMonthChart")
        .append("svg")
        .attr("width", 500)
        .attr("height", 500);

    const margin = { top: 30, right: 20, bottom: 60, left: 50 };
    const width = +svg.attr("width") - margin.left - margin.right;
    const height = +svg.attr("height") - margin.top - margin.bottom;
    const g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    const x = d3.scaleBand()
        .range([0, width])
        .padding(0.1);

    const y = d3.scaleLinear()
        .range([height, 0]);

    const salesData = sales_by_month.map(d => ({
        month: new Date(d.month),
        sales: +d.sales
    }));

    const monthYearFormat = d3.timeFormat("%Y %b"); // Formato: abreviatura del mes y año (ejemplo: Jan 2023)

    x.domain(salesData.map(d => monthYearFormat(d.month)));
    y.domain([0, d3.max(salesData, d => d.sales)]);

    const line = d3.line()
        .x(d => x(monthYearFormat(d.month)) + x.bandwidth() / 2)
        .y(d => y(d.sales));

    const color = "#2382ac";

    g.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x))
        .selectAll("text")
        .attr("transform", "translate(-10,0)rotate(-45)")
        .style("text-anchor", "end");

    g.append("g")
        .call(d3.axisLeft(y));

    const tooltip = d3.select("#tooltip");

    g.append("path")
        .datum(salesData)
        .attr("fill", "none")
        .attr("stroke", color)
        .attr("stroke-width", 3) // Ajusta el ancho de la línea
        .attr("d", line);

    g.selectAll(".dot")
        .data(salesData)
        .enter().append("circle")
        .attr("class", "dot")
        .attr("cx", d => x(monthYearFormat(d.month)) + x.bandwidth() / 2)
        .attr("cy", d => y(d.sales))
        .attr("r", 6) // Ajusta el tamaño del punto
        .attr("fill", color)
        .on("mouseover", function (event, d) {
            const currentColor = d3.color(d3.select(this).attr("fill"));
            const lighterColor = currentColor.brighter(0.2);
            d3.select(this)
                .attr("fill", lighterColor);

            tooltip.style("left", event.pageX + "px")
                .style("top", event.pageY + "px")
                .classed("show", true)
                .html(`<span>${formatCurrency(d.sales)}</span>`);
        })
        .on("mouseout", function () {
            d3.select(this)
                .attr("fill", color);

            tooltip.classed("show", false);
        });
}

onMounted(() => {
    generateSalesByMonthChart();
});

onUnmounted(() => {
    d3.select("#salesByMonthChart").selectAll("*").remove();
});
</script>

<template>
    <div id="sales-by-month" class="bg-white p-6 rounded shadow">
        <h2 class="font-semibold text-lg mb-4">Sales By Month</h2>
        <div id="salesByMonthChart">
        </div>
    </div>
</template>
