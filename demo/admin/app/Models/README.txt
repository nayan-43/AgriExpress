AgriExpress Laravel 13 Models

Copy all 21 PHP files to app/Models/.

Models: User, Category, Brand, Attribute, AttributeValue, Product, ProductImage, ProductVariant, VariantAttributeValue, Address, Cart, CartItem, Wishlist, WishlistItem, Coupon, Order, OrderItem, OrderAddress, Payment, CouponUsage, Review.

The relationships match the migrations created previously. ProductVariant <-> AttributeValue uses variant_attribute_values. OrderItem and OrderAddress retain historical snapshots. CouponUsage retains coupon_code for historical records.
