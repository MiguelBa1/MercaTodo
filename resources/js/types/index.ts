export interface OrderDetail {
    id: number;
    order_id: number;
    product_name: string;
    product_price: string;
    quantity: number;
}

export interface Order {
    id: number;
    reference: string;
    process_url: string;
    status: string;
    total: string;
    created_at: Date;
    order_details: OrderDetail[];
}
