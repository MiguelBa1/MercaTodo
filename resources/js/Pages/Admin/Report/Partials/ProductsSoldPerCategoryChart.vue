<script setup>
import * as d3 from "d3";
import {onMounted, onUnmounted} from "vue";

const { products_sold_per_category } = defineProps({
    products_sold_per_category: {
        type: Array,
        required: true
    }
});

function generateProductsSoldPerCategoryChart() {
    const svg = d3.select("#productsSoldPerCategoryChart")
        .append("svg")
        .attr("width", 500)
        .attr("height", 500);

    const margin = { top: 20, right: 20, bottom: 100, left: 40 };
    const width = +svg.attr("width") - margin.left - margin.right;
    const height = +svg.attr("height") - margin.top - margin.bottom;
    const g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    const x = d3.scaleBand()
        .rangeRound([0, width])
        .padding(0.1);

    const y = d3.scaleLinear()
        .rangeRound([height, 0]);

    const data = products_sold_per_category.map(d => ({
        category_name: d.category_name,
        quantity: +d.quantity
    }));

    x.domain(data.map(d => d.category_name));
    y.domain([0, d3.max(data, d => d.quantity)]);

    const color = d3.scaleOrdinal(d3.schemeTableau10);

    const tooltip = d3.select("#tooltip");

    g.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x))
        .selectAll("text")
        .attr("transform", "translate(-10,0)rotate(-45)")
        .style("text-anchor", "end")
        .attr("dy", "0.35em")
        .attr("dx", "-0.8em")
        .attr("font-size", "12px") // Ajusta el tamaño de fuente de los textos
        .attr("fill", "#000") // Ajusta el color de los textos;

    g.append("g")
        .call(d3.axisLeft(y))
        .append("text")
        .attr("fill", "#000")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", "0.71em")
        .attr("text-anchor", "end")
        .text("Quantity");

    g.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .attr("class", "bar")
        .attr("x", d => x(d.category_name))
        .attr("y", d => y(d.quantity))
        .attr("width", x.bandwidth())
        .attr("height", d => height - y(d.quantity))
        .attr("fill", d => color(d.category_name))
        .on("mouseover", function (event, d) {
            const currentColor = d3.color(d3.select(this).attr("fill"));
            const lighterColor = currentColor.brighter(0.2);
            d3.select(this)
                .attr("fill", lighterColor);

            tooltip.style("left", event.pageX + "px")
                .style("top", event.pageY + "px")
                .classed("show", true)
                .html(`<span>${d.quantity}</span>`);
        })
        .on("mouseout", function () {
            d3.select(this)
                .attr("fill", d => color(d.category_name));

            tooltip.classed("show", false);
        });
}

onMounted(() => {
    generateProductsSoldPerCategoryChart();
});

onUnmounted(() => {
    d3.select("#productsSoldPerCategoryChart").selectAll("*").remove();
});
</script>

<template>
    <div id="products-sold-per-category" class="bg-white p-6 rounded shadow">
        <h2 class="font-semibold text-lg mb-4">Products Sold per Category</h2>
        <div id="productsSoldPerCategoryChart"></div>
    </div>
</template>
