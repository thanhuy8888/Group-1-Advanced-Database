CREATE DATABASE Group1 --Database chung, đừng xóa
USE Group1
--1. Order Management
CREATE TABLE Customer (
  Name VARCHAR(50),
  Phone Number VARCHAR(10),
  Address VARCHAR (50),
  Email VARCHAR (20)
  );
CREATE TABLE Employee (
    EmployeeID INT PRIMARY KEY,
    Name VARCHAR(100),
    Position VARCHAR(50),
    Department VARCHAR(50),
    Email VARCHAR(100)
);

CREATE TABLE "Order" (
    OrderID INT PRIMARY KEY,
    CustomerID INT,
    OrderDate DATE,
    Status VARCHAR(20),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    Name VARCHAR(100),
    Description TEXT,
    Price DECIMAL(10, 2),
    StockOfQuantity INT
);

CREATE TABLE OrderDetail (
    OrderDetailID INT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT,
    Price DECIMAL(10, 2),
    FOREIGN KEY (OrderID) REFERENCES "Order"(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
--2. Inventory Tracking
CREATE TABLE Warehouse (
    WarehouseID INT PRIMARY KEY,
    Name VARCHAR(100),
    Location VARCHAR(255)
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    Name VARCHAR(100),
    Description TEXT,
    Price DECIMAL(10, 2),
    StockOfQuantity INT
);

CREATE TABLE Inventory (
    InventoryID INT PRIMARY KEY,
    WarehouseID INT,
    ProductID INT,
    Quantity INT,
    LastUpdated DATE,
    FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE Supplier (
    SupplierID INT PRIMARY KEY,
    Name VARCHAR(100),
    PhoneContact VARCHAR(15),
    Address VARCHAR(255)
);

CREATE TABLE Shipment (
    ShipmentID INT PRIMARY KEY,
    EmployeeID INT,
    FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID)
);

CREATE TABLE ShipmentDetail (
    ShipmentDetailID INT PRIMARY KEY,
    ShipmentID INT,
    ProductID INT,
    Email VARCHAR(100),
    FOREIGN KEY (ShipmentID) REFERENCES Shipment(ShipmentID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
--3. Route Planning and Optimization
CREATE TABLE Vehicle (
    VehicleID INT PRIMARY KEY,
    LicenseDate DATE,
    Capacity INT,
    Type VARCHAR(50),
    Status VARCHAR(20)
);

CREATE TABLE Driver (
    DriverID INT PRIMARY KEY,
    Name VARCHAR(100),
    Location VARCHAR(255)
);

CREATE TABLE Route (
    RouteID INT PRIMARY KEY,
    Name VARCHAR(100),
    TotalDistance DECIMAL(10, 2),
    EstimatedTime DECIMAL(5, 2),
    StartLocation VARCHAR(255),
    EndLocation VARCHAR(255)
);

CREATE TABLE RoutePlan (
    RoutePlanID INT PRIMARY KEY,
    RouteID INT,
    VehicleID INT,
    DriverID INT,
    Email VARCHAR(100),
    FOREIGN KEY (RouteID) REFERENCES Route(RouteID),
    FOREIGN KEY (VehicleID) REFERENCES Vehicle(VehicleID),
    FOREIGN KEY (DriverID) REFERENCES Driver(DriverID)
);

CREATE TABLE "Order" (
    OrderID INT PRIMARY KEY,
    CustomerID INT,
    OrderDate DATE,
    Status VARCHAR(20),
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);
--4. Customer Management 

--5. Reporting and Analytics

--6. User Access Control 

--7. Multi-location Support 

CREATE TABLE Location (
    LocationID INT PRIMARY KEY,
    LocationName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    Description TEXT,
    UnitPrice DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Inventory (
    InventoryID INT PRIMARY KEY,
    LocationID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE `Order` (
    OrderID INT PRIMARY KEY,
    OrderDate DATE NOT NULL,
    LocationID INT,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID)
);

CREATE TABLE OrderDetail (
    OrderDetailID INT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    Subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
--8. Mobile Access
CREATE TABLE User (
    UserID INT PRIMARY KEY,
    UserName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE MobileDevice (
    DeviceID INT PRIMARY KEY,
    UserID INT,
    DeviceType VARCHAR(50) NOT NULL,
    DeviceToken VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES User(UserID)
);

CREATE TABLE Location (
    LocationID INT PRIMARY KEY,
    LocationName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    Description TEXT,
    UnitPrice DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Inventory (
    InventoryID INT PRIMARY KEY,
    LocationID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE `Order` (
    OrderID INT PRIMARY KEY,
    OrderDate DATE NOT NULL,
    LocationID INT,
    UserID INT,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID),
    FOREIGN KEY (UserID) REFERENCES User(UserID)
);

CREATE TABLE OrderDetail (
    OrderDetailID INT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    Subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE MobileAccess (
    MobileAccessID INT PRIMARY KEY,
    UserID INT,
    DeviceID INT,
    AccessTime DATETIME NOT NULL,
    LocationID INT,
    FOREIGN KEY (UserID) REFERENCES User(UserID),
    FOREIGN KEY (DeviceID) REFERENCES MobileDevice(DeviceID),
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID)
);

--9. Integration with ERP
CREATE TABLE User (
    UserID INT PRIMARY KEY,
    UserName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE ERPSystem (
    ERPSystemID INT PRIMARY KEY,
    ERPSystemName VARCHAR(255) NOT NULL
);

CREATE TABLE ERPIntegration (
    ERPIntegrationID INT PRIMARY KEY,
    UserID INT,
    ERPSystemID INT,
    IntegrationDate DATE NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES User(UserID),
    FOREIGN KEY (ERPSystemID) REFERENCES ERPSystem(ERPSystemID)
);

CREATE TABLE Location (
    LocationID INT PRIMARY KEY,
    LocationName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    Description TEXT,
    UnitPrice DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Inventory (
    InventoryID INT PRIMARY KEY,
    LocationID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE `Order` (
    OrderID INT PRIMARY KEY,
    OrderDate DATE NOT NULL,
    LocationID INT,
    UserID INT,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Location(LocationID),
    FOREIGN KEY (UserID) REFERENCES User(UserID)
);

CREATE TABLE OrderDetail (
    OrderDetailID INT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    Subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
--10. Real-time Updates

CREATE TABLE inventory (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    stock_quantity INT NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    order_status ENUM('Pending', 'Processing', 'Shipped', 'Delivered') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES inventory(product_id)
);

CREATE TABLE shipments (
    shipment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    shipment_status ENUM('In Transit', 'Delivered') DEFAULT 'In Transit',
    shipment_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
);

--11. Audit Trail 
CREATE TABLE audit_trail (
    audit_id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(255) NOT NULL,
    action_type ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    record_id INT NOT NULL,
    changed_data TEXT, -- JSON representation of changed fields
    changed_by VARCHAR(255), -- User or system making the change
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--12. Notifications
CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Recipient user ID
    notification_type ENUM('Inventory', 'Order', 'Shipment') NOT NULL, -- Event type
    event_id INT NOT NULL, -- ID of the associated event (product_id, order_id, or shipment_id)
    message TEXT NOT NULL, -- Notification message
    is_read BOOLEAN DEFAULT FALSE, -- Status of the notification
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Time the notification was created
);

