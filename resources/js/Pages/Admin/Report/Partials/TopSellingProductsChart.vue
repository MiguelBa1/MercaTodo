<script setup>
import * as d3 from "d3";
import {onMounted, onUnmounted} from "vue";

const { top_selling_products } = defineProps({
    top_selling_products: {
        type: Array,
        required: true
    }
});

function generateTopSellingProductsChart() {
    const width = 600;
    const height = 600;
    const radius = Math.min(width, height) / 2;
    const color = d3.scaleOrdinal(d3.schemeTableau10);

    const svg = d3.select("#topSellingProductsChart")
        .append("svg")
        .attr("width", width)
        .attr("height", height)
        .append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    const arc = d3.arc()
        .outerRadius(radius - 10)
        .innerRadius(0);

    const pie = d3.pie()
        .sort(null)
        .value(d => d.total);

    const arcs = svg.selectAll(".arc")
        .data(pie(top_selling_products))
        .enter()
        .append("g")
        .attr("class", "arc")
        .on("mouseover", function (event, d) {
            const currentColor = d3.select(this).select("path").attr("fill");
            const lighterColor = d3.color(currentColor).brighter(0.2);
            d3.select(this).select("path")
                .attr("fill", lighterColor);

            const tooltip = d3.select("#tooltip");

            tooltip.style("left", event.pageX + "px")
                .style("top", event.pageY + "px")
                .classed("show", true)
                .html(`<span>${d.data.total}</span>`);
        })
        .on("mouseout", function () {
            const originalColor = d3.select(this).attr("data-color");
            d3.select(this).select("path")
                .attr("fill", originalColor);

            d3.select("#tooltip").classed("show", false);
        });

    arcs.append("path")
        .attr("d", arc)
        .attr("fill", function (d) {
            const sectionColor = color(d.data.product_name);
            d3.select(this.parentNode).attr("data-color", sectionColor);
            return sectionColor;
        });

    arcs.append("text")
        .attr("transform", function (d) {
            const centroid = arc.centroid(d);
            const x = centroid[0] * 1.5;
            const y = centroid[1] * 1.5;
            return "translate(" + x + "," + y + ")";
        })
        .attr("dy", ".35em")
        .text(d => `${d.data.product_name} (${d.data.total})`)
        .style("text-anchor", "middle");
}

onMounted(() => {
    generateTopSellingProductsChart();
});

onUnmounted(() => {
    d3.select("#topSellingProductsChart").selectAll("*").remove();
});
</script>

<template>
    <div id="top-selling-products" class="bg-white p-6 rounded shadow flex flex-col items-center">
        <h2 class="font-semibold text-lg mb-4">Top Selling Products</h2>
        <div id="topSellingProductsChart"></div>
    </div>
</template>
