<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import {defineOptions} from "vue";
import ProductsSoldPerCategoryChart from "@/Pages/Admin/Report/Partials/ProductsSoldPerCategoryChart.vue";
import TopSellingProductsChart from "@/Pages/Admin/Report/Partials/TopSellingProductsChart.vue";
import SalesByMonthChart from "@/Pages/Admin/Report/Partials/SalesByMonthChart.vue";
import {formatCurrency} from "@/Utils/currency";

defineOptions({
    layout: MainLayout
});

const {report} = usePage().props;

let {
    total_sales,
    total_orders,
    sales_by_month,
    most_sold_product,
    top_selling_products,
    products_sold_per_category
} = report.data;

</script>

<template>
    <Head>
        <title>Reports</title>
    </Head>
    <div class="p-6 lg:p-12 max-w-7xl mx-auto grid gap-3">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="font-semibold text-lg mb-4">Total Sales</h2>
                <p class="text-4xl">{{ formatCurrency(total_sales) }}$</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="font-semibold text-lg mb-4">Total Orders</h2>
                <p class="text-4xl">{{ total_orders }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h2 class="font-semibold text-lg mb-4">Most Sold Product</h2>
                <p class="text-2xl">{{ most_sold_product.product_name }}</p>
                <p class="text-xl">Sold {{ most_sold_product.quantity }} times</p>
            </div>
        </div>

        <div id="tooltip" class="tooltip"></div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 ">
            <SalesByMonthChart :sales_by_month="sales_by_month"/>
            <ProductsSoldPerCategoryChart :products_sold_per_category="products_sold_per_category"/>
        </div>

        <TopSellingProductsChart :top_selling_products="top_selling_products"/>
    </div>
</template>

<style>
.tooltip {
    position: absolute;
    display: none;
    padding: 8px;
    background-color: rgba(0, 0, 0, 0.8);
    color: #fff;
    font-size: 14px;
    border-radius: 4px;
}

.tooltip.show {
    display: block;
}
</style>
