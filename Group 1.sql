CREATE DATABASE Group1; -- Database chung, đừng xóa
USE Group1;

-- 1. Order Management
CREATE TABLE Customers (
  CustomerID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  PhoneNumber VARCHAR(15),
  Address VARCHAR(255),
  Email VARCHAR(100)
);

CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    OrderDate DATE,
    Status VARCHAR(50),
    PaymentStatus ENUM('Pending', 'Paid', 'Failed') DEFAULT 'Pending', -- Thêm trạng thái thanh toán
    PaymentMethod VARCHAR(50), -- Thêm phương thức thanh toán
    DeliveryDate DATE, -- Thêm ngày giao hàng
    ShippingAddress VARCHAR(255), -- Thêm địa chỉ giao hàng nếu khác với địa chỉ khách hàng
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
);

CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(150),
    Description TEXT,
    Price DECIMAL(10, 2),
    StockQuantity INT,
    ReorderLevel INT, -- Thêm mức đặt hàng lại
    LastRestockDate DATE -- Thêm ngày restock gần nhất
);

CREATE TABLE OrderDetails (
    OrderDetailID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT,
    Price DECIMAL(10, 2),
    Discount DECIMAL(5, 2) DEFAULT 0, -- Thêm giảm giá cho sản phẩm
    Tax DECIMAL(5, 2) DEFAULT 0, -- Thêm thuế cho sản phẩm
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- 2. Inventory Tracking
CREATE TABLE Warehouses (
    WarehouseID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(150),
    Location VARCHAR(255)
);

CREATE TABLE InventoryItems (
    InventoryID INT AUTO_INCREMENT PRIMARY KEY,
    WarehouseID INT,
    ProductID INT,
    Quantity INT,
    LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (WarehouseID) REFERENCES Warehouses(WarehouseID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- Thêm bảng dự báo nhu cầu
CREATE TABLE DemandForecasts (
    ForecastID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT,
    ForecastDate DATE,
    PredictedQuantity INT,
    Accuracy DECIMAL(5, 2), -- Thêm độ chính xác của dự báo
    Deviation DECIMAL(5, 2), -- Thêm độ lệch của dự báo
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- 3. Route Planning and Optimization
CREATE TABLE Vehicles (
    VehicleID INT AUTO_INCREMENT PRIMARY KEY,
    LicensePlate VARCHAR(50),
    Capacity INT,
    Type VARCHAR(50),
    Status VARCHAR(50)
);

CREATE TABLE Drivers (
    DriverID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Location VARCHAR(255)
);

CREATE TABLE Routes (
    RouteID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(150),
    TotalDistance DECIMAL(10, 2),
    EstimatedTime DECIMAL(5, 2),
    StartLocation VARCHAR(255),
    EndLocation VARCHAR(255),
    RouteType VARCHAR(50) -- Thêm loại tuyến đường (e.g., Express, Regular)
);

CREATE TABLE RoutePlans (
    RoutePlanID INT AUTO_INCREMENT PRIMARY KEY,
    RouteID INT,
    VehicleID INT,
    DriverID INT,
    StartTime DATETIME, -- Thêm thời gian bắt đầu
    EndTime DATETIME, -- Thêm thời gian kết thúc
    FOREIGN KEY (RouteID) REFERENCES Routes(RouteID),
    FOREIGN KEY (VehicleID) REFERENCES Vehicles(VehicleID),
    FOREIGN KEY (DriverID) REFERENCES Drivers(DriverID)
);

-- 4. Customer Management (Dùng lại bảng Customers từ Order Management)
CREATE TABLE CustomerBehavior (
    BehaviorID INT PRIMARY KEY,
    CustomerID INT,
    ActionType VARCHAR(255),
    ActionDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
);

-- 5. Reporting and Analytics
CREATE TABLE Reports (
    ReportID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Type ENUM('Sales', 'Inventory', 'Customer') NOT NULL,
    GeneratedBy INT NOT NULL,
    GeneratedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (GeneratedBy) REFERENCES Users(UserID)
);

CREATE TABLE ReportData (
    ReportDataID INT AUTO_INCREMENT PRIMARY KEY,
    ReportID INT NOT NULL,
    DataKey VARCHAR(255) NOT NULL,
    DataValue VARCHAR(255),
    FOREIGN KEY (ReportID) REFERENCES Reports(ReportID)
);

-- 6. User Access Control
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    RoleID INT NOT NULL,
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
);

CREATE TABLE Roles (
    RoleID INT AUTO_INCREMENT PRIMARY KEY,
    RoleName VARCHAR(255) NOT NULL UNIQUE,
    Description TEXT
);

CREATE TABLE Permissions (
    PermissionID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL UNIQUE,
    Description TEXT
);

CREATE TABLE RolePermissions (
    RolePermissionID INT AUTO_INCREMENT PRIMARY KEY,
    RoleID INT NOT NULL,
    PermissionID INT NOT NULL,
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID),
    FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
);

-- 7. Multi-location Support
CREATE TABLE Locations (
    LocationID INT AUTO_INCREMENT PRIMARY KEY,
    LocationName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL
);

CREATE TABLE InventoryLocations (
    InventoryLocationID INT AUTO_INCREMENT PRIMARY KEY,
    LocationID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

-- 8. Mobile Access
CREATE TABLE MobileDevices (
    DeviceID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    DeviceType VARCHAR(50) NOT NULL,
    DeviceToken VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

CREATE TABLE MobileAccessLogs (
    MobileAccessID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    DeviceID INT,
    AccessTime DATETIME NOT NULL,
    LocationID INT,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (DeviceID) REFERENCES MobileDevices(DeviceID),
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID)
);

-- 9. Integration with ERP
CREATE TABLE ERPSystems (
    ERPSystemID INT AUTO_INCREMENT PRIMARY KEY,
    ERPSystemName VARCHAR(255) NOT NULL
);

CREATE TABLE ERPIntegrations (
    ERPIntegrationID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ERPSystemID INT,
    IntegrationDate DATE NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (ERPSystemID) REFERENCES ERPSystems(ERPSystemID)
);

-- 10. Real-time Updates
CREATE TABLE RealTimeInventory (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    StockQuantity INT NOT NULL,
    LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE RealTimeOrders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    OrderStatus ENUM('Pending', 'Processing', 'Shipped', 'Delivered') DEFAULT 'Pending',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UpdatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ProductID) REFERENCES RealTimeInventory(ProductID)
);

CREATE TABLE Shipments (
    ShipmentID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    ShipmentStatus ENUM('In Transit', 'Delivered') DEFAULT 'In Transit',
    ShipmentTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OrderID) REFERENCES RealTimeOrders(OrderID)
);

-- 11. Audit Trail
CREATE TABLE AuditTrails (
    AuditID INT AUTO_INCREMENT PRIMARY KEY,
    TableName VARCHAR(255) NOT NULL,
    ActionType ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    RecordID INT NOT NULL,
    ChangedData TEXT, -- JSON representation of changed fields
    ChangedBy VARCHAR(255), -- User or system making the change
    ChangedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 12. Notifications
CREATE TABLE Notifications (
    NotificationID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL, -- Recipient user ID
    NotificationType ENUM('Inventory', 'Order', 'Shipment') NOT NULL, -- Event type
    NotificationMessage TEXT NOT NULL,
    Status ENUM('Unread', 'Read') DEFAULT 'Unread',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
