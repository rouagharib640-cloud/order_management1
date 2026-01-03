<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - {{ $order->order_number }}</title>
    <style>
        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 20px;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        /* Header */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .header-title {
            font-size: 32px;
            font-weight: 700;
            margin: 20px 0 10px;
        }
        
        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }
        
        /* Content */
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #4b5563;
            margin-bottom: 30px;
        }
        
        .greeting-name {
            color: #1f2937;
            font-weight: 600;
        }
        
        /* Status Card */
        .status-card {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            text-align: center;
        }
        
        .status-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .status-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .status-message {
            font-size: 16px;
            opacity: 0.9;
        }
        
        /* Order Details */
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .order-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-item {
            background: #f9fafb;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
        }
        
        .summary-label {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .summary-value {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0 30px;
        }
        
        .items-table th {
            background: #f3f4f6;
            color: #4b5563;
            font-weight: 600;
            text-align: left;
            padding: 15px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .item-name {
            font-weight: 500;
            color: #1f2937;
        }
        
        .item-price {
            color: #059669;
            font-weight: 600;
        }
        
        .item-quantity {
            color: #6b7280;
        }
        
        .item-subtotal {
            font-weight: 700;
            color: #1f2937;
        }
        
        /* Total Section */
        .total-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .total-row:last-child {
            border-bottom: none;
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            padding-top: 15px;
            margin-top: 10px;
        }
        
        /* Addresses */
        .addresses {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin: 30px 0;
        }
        
        .address-card {
            background: #f9fafb;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }
        
        .address-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .address-content {
            color: #6b7280;
            line-height: 1.7;
        }
        
        /* CTA Buttons */
        .cta-section {
            text-align: center;
            margin: 40px 0;
        }
        
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 0 10px;
        }
        
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .cta-secondary {
            background: #6b7280;
        }
        
        /* Footer */
        .email-footer {
            background: #f3f4f6;
            padding: 30px;
            text-align: center;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .footer-link {
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
        }
        
        .footer-link:hover {
            color: #3b82f6;
        }
        
        .footer-text {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .email-content {
                padding: 25px 20px;
            }
            
            .order-summary {
                grid-template-columns: 1fr;
            }
            
            .addresses {
                grid-template-columns: 1fr;
            }
            
            .cta-button {
                display: block;
                margin: 10px 0;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">
                <div class="logo-icon">📦</div>
                <span>OrderFlow Pro</span>
            </div>
            <h1 class="header-title">Order Confirmed!</h1>
            <p class="header-subtitle">Your order has been successfully confirmed and is being processed</p>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- Greeting -->
            <div class="greeting">
                Hi <span class="greeting-name">{{ $order->customer_name }}</span>,
            </div>
            
            <p>Thank you for your order! We're excited to let you know that your order <strong>#{{ $order->order_number }}</strong> has been confirmed and is now being prepared for shipment.</p>
            
            <!-- Status Card -->
            <div class="status-card">
                <div class="status-icon">✅</div>
                <h2 class="status-title">Order Status: Confirmed</h2>
                <p class="status-message">Your order is now in the queue for processing and shipping</p>
            </div>
            
            <!-- Order Summary -->
            <h2 class="section-title">Order Summary</h2>
            <div class="order-summary">
                <div class="summary-item">
                    <div class="summary-label">Order Number</div>
                    <div class="summary-value">#{{ $order->order_number }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Order Date</div>
                    <div class="summary-value">{{ $order->created_at->format('F d, Y') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Order Time</div>
                    <div class="summary-value">{{ $order->created_at->format('h:i A') }}</div>
                </div>
                <div class="summary-item">
                    <div class="summary-label">Items</div>
                    <div class="summary-value">{{ $order->items->count() }} items</div>
                </div>
            </div>
            
            <!-- Order Items -->
            <h2 class="section-title">Order Items</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="item-name">{{ $item->product_name }}</td>
                            <td class="item-price">${{ number_format($item->price, 2) }}</td>
                            <td class="item-quantity">{{ $item->quantity }}</td>
                            <td class="item-subtotal">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Order Total -->
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_cost, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping</span>
                    <span>${{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Total Amount</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
            
            <!-- Addresses -->
            <div class="addresses">
                <div class="address-card">
                    <h3 class="address-title">📦 Shipping Address</h3>
                    <div class="address-content">
                        {{ nl2br($order->shipping_address) }}
                    </div>
                </div>
                <div class="address-card">
                    <h3 class="address-title">💳 Billing Address</h3>
                    <div class="address-content">
                        {{ nl2br($order->billing_address) }}
                    </div>
                </div>
            </div>
            
            <!-- CTA Buttons -->
            <div class="cta-section">
                <!-- Lien modifié pour voir les détails de la commande -->
                <a href="http://localhost:8000/orders/{{ $order->id }}" class="cta-button">
                    View Order Details
                </a>
                <!-- Lien modifié pour la page d'accueil des commandes -->
                <a href="http://localhost:8000/orders" class="cta-button cta-secondary">
                    View All Orders
                </a>
            </div>
            
            <!-- Next Steps -->
            <h2 class="section-title">What's Next?</h2>
            <div style="background: #f0f9ff; padding: 20px; border-radius: 10px; border-left: 4px solid #0ea5e9; margin: 20px 0;">
                <p><strong>📦 Processing:</strong> Your order is being prepared (1-2 business days)</p>
                <p><strong>🚚 Shipping:</strong> You'll receive tracking information once shipped</p>
                <p><strong>📱 Tracking:</strong> Track your order anytime in your account</p>
            </div>
            
            <!-- Notes -->
            @if($order->notes)
                <h2 class="section-title">Order Notes</h2>
                <div style="background: #fef3c7; padding: 20px; border-radius: 10px; border-left: 4px solid #f59e0b;">
                    <p><strong>📝 Special Instructions:</strong> {{ $order->notes }}</p>
                </div>
            @endif
            
            <!-- Support -->
            <p style="margin-top: 30px; color: #6b7280; font-size: 15px;">
                Need help? Contact our support team at 
                <a href="mailto:support@orderflowpro.com" style="color: #3b82f6;">support@orderflowpro.com</a> 
                or call us at +1 (555) 123-4567.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <!-- Liens modifiés pour votre environnement local -->
                <a href="http://localhost:8000/orders" class="footer-link">My Orders</a>
                <a href="http://localhost:8000/contact" class="footer-link">Contact Us</a>
                <a href="http://localhost:8000/faq" class="footer-link">FAQ</a>
            </div>
            <p class="footer-text">
                © {{ date('Y') }} OrderFlow Pro. All rights reserved.<br>
                123 Commerce Street, Business City, BC 10001
            </p>
            <p class="footer-text" style="font-size: 12px; margin-top: 10px;">
                This is an automated email, please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>