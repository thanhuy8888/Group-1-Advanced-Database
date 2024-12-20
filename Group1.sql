CREATE DATABASE Group1;
Use Group1;
CREATE TABLE Customers (
  CustomerID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(100),
  PhoneNumber VARCHAR(15),
  Address VARCHAR(255),
  Email VARCHAR(100),
  DateOfBirth DATE,
  Gender VARCHAR(10),
  JoinDate DATE ,
  LoyaltyPoints INT DEFAULT 0
);
LOAD DATA INFILE 'E:\\Data\\Order_Management\\Customer(1).csv'
INTO TABLE Customers
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE PaymentStatuses (
  PaymentStatusID INT AUTO_INCREMENT PRIMARY KEY,
  StatusName VARCHAR(50) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Order_Management\\PaymentStatus.csv'
INTO TABLE PaymentStatuses
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT,
    PaymentStatusID INT,
    OrderDate DATE,
    Status VARCHAR(50),
    PaymentMethod VARCHAR(50),
    DeliveryDate DATE,
    ShippingAddress VARCHAR(255),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (PaymentStatusID) REFERENCES PaymentStatuses(PaymentStatusID)
);
LOAD DATA INFILE 'E:\\Data\\Order_Management\\Orders(1).csv'
INTO TABLE Orders
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(150),
    Description TEXT,
    Price DECIMAL(10, 2),
    StockQuantity INT,
    ReorderLevel INT,
    LastRestockDate DATE
);
LOAD DATA INFILE 'E:\\Data\\Order_Management\\Products.csv'
INTO TABLE Products
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE OrderDetails (
    OrderDetailID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT,
    Price DECIMAL(10, 2),
    Discount DECIMAL(5, 2) DEFAULT 0,
    Tax DECIMAL(5, 2) DEFAULT 0,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);

LOAD DATA INFILE 'E:\\Data\\Order_Management\\OrderDetails.csv'
INTO TABLE OrderDetails
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- 2. Inventory Tracking
CREATE TABLE Warehouses (
    WarehouseID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(150),
    Location VARCHAR(255)
);

LOAD DATA INFILE 'E:\\Data\\Inventory_tracking\\Warehouses.csv'
INTO TABLE Warehouses
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE InventoryItems (
    InventoryID INT AUTO_INCREMENT PRIMARY KEY,
    WarehouseID INT,
    ProductID INT,
    Quantity INT,
    LastUpdated TIME,
    FOREIGN KEY (WarehouseID) REFERENCES Warehouses(WarehouseID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);
LOAD DATA INFILE 'E:\\Data\\Inventory_tracking\\Inventory_items.csv'
INTO TABLE InventoryItems
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;


-- 3. Route Planning and Optimization
CREATE TABLE Vehicles (
    VehicleID INT AUTO_INCREMENT PRIMARY KEY,
    LicensePlate VARCHAR(50),
    Capacity INT,
    Type VARCHAR(50),
    Status VARCHAR(50)
);

LOAD DATA INFILE 'E:\\Data\\Route Planning and Optimization\\Vehicle1.csv'
INTO TABLE Vehicles
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Drivers (
    DriverID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100),
    Location VARCHAR(255)
);

LOAD DATA INFILE 'E:\\Data\\Route Planning and Optimization\\Drivers.csv'
INTO TABLE Drivers
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE RouteTypes (
  RouteTypeID INT AUTO_INCREMENT PRIMARY KEY,
  Type VARCHAR(50) NOT NULL
);

LOAD DATA INFILE 'E:\\Data\\Route Planning and Optimization\\Route_type.csv'
INTO TABLE RouteTypes
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Routes (
    RouteID INT AUTO_INCREMENT PRIMARY KEY,
    RouteTypeID INT,
    Name VARCHAR(150),
    TotalDistance DECIMAL(10, 2),
    EstimatedTime DECIMAL(5, 2),
    StartLocation VARCHAR(255),
    EndLocation VARCHAR(255),
    FOREIGN KEY (RouteTypeID) REFERENCES RouteTypes(RouteTypeID)
);
LOAD DATA INFILE 'E:\\Data\\Route Planning and Optimization\\Routes.csv'
INTO TABLE Routes
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE RoutePlans (
    RoutePlanID INT AUTO_INCREMENT PRIMARY KEY,
    RouteID INT,
    VehicleID INT,
    DriverID INT,
    StartTime TIME,
    EndTime TIME,
    FOREIGN KEY (RouteID) REFERENCES Routes(RouteID),
    FOREIGN KEY (VehicleID) REFERENCES Vehicles(VehicleID),
    FOREIGN KEY (DriverID) REFERENCES Drivers(DriverID)
);

LOAD DATA INFILE 'E:\\Data\\Route Planning and Optimization\\Route_Plans.csv'
INTO TABLE RoutePlans
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 4. Customer Management
CREATE TABLE ActionTypes (
    ActionTypeID INT AUTO_INCREMENT PRIMARY KEY,
    ActionName VARCHAR(255) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Customer_management\\Action_Types.csv'
INTO TABLE ActionTypes
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE CustomerBehavior (
    BehaviorID INT AUTO_INCREMENT PRIMARY KEY, 
    CustomerID INT,
    ActionTypeID INT,
    ActionDate DATE,
    Time TIME,
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
    FOREIGN KEY (ActionTypeID) REFERENCES ActionTypes(ActionTypeID)
);

LOAD DATA INFILE 'E:\\Data\\Customer_management\\Customer_Behavior.csv'
INTO TABLE CustomerBehavior
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE InteractionTypes (
  InteractionTypeID INT AUTO_INCREMENT PRIMARY KEY,
  InteractionTypeName VARCHAR(100) NOT NULL 
);
LOAD DATA INFILE 'E:\\Data\\Customer_management\\InteractionTypes.csv'
INTO TABLE InteractionTypes
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- Customer Interactions
CREATE TABLE CustomerInteractions (
  InteractionID INT AUTO_INCREMENT PRIMARY KEY,
  CustomerID INT,
  InteractionTypeID INT,
  InteractionDate DATE,
  Time TIME,
  Notes TEXT,
  FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
  FOREIGN KEY (InteractionTypeID) REFERENCES InteractionTypes(InteractionTypeID)
);
LOAD DATA INFILE 'E:\\Data\\Customer_management\\Customer_Interactions.csv'
INTO TABLE CustomerInteractions
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE CustomerSegments (
  SegmentID INT AUTO_INCREMENT PRIMARY KEY,
  SegmentName VARCHAR(100) NOT NULL,
  Description TEXT 
);
LOAD DATA INFILE 'E:\\Data\\Customer_management\\Customer_segment.csv'
INTO TABLE CustomerSegments
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE CustomerSegmentMappings (
  MappingID INT AUTO_INCREMENT PRIMARY KEY,
  CustomerID INT,
  SegmentID INT,
  AssignedDate DATE,
  Time TIME,
  FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID),
  FOREIGN KEY (SegmentID) REFERENCES CustomerSegments(SegmentID)
);
LOAD DATA INFILE 'E:\\Data\\Customer_management\\Customer_segment_mapping.csv'
INTO TABLE CustomerSegmentMappings
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- 6. User Access Control
CREATE TABLE Roles (
    RoleID INT AUTO_INCREMENT PRIMARY KEY,
    RoleName VARCHAR(255) NOT NULL UNIQUE,
    Description TEXT
);
LOAD DATA INFILE 'E:\\Data\\User Access Control\\Roles.csv'
INTO TABLE Roles
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    RoleID INT NOT NULL,
    CreatedAtDate DATE,
	CreatedAtTime TIME,
    UpdatedAtDate DATE,
    UpdatedAtTime TIME,
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
);
LOAD DATA INFILE 'E:\\Data\\User Access Control\\Users.csv'
INTO TABLE Users
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Permissions (
    PermissionID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL UNIQUE,
    Description TEXT
);
LOAD DATA INFILE 'E:\\Data\\User Access Control\\Permissions.csv'
INTO TABLE Permissions
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE RolePermissions (
    RolePermissionID INT AUTO_INCREMENT PRIMARY KEY,
    RoleID INT NOT NULL,
    PermissionID INT NOT NULL,
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID),
    FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
);
LOAD DATA INFILE 'E:\\Data\\User Access Control\\RolePermissions.csv'
INTO TABLE RolePermissions
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 5. Reporting and Analytics
CREATE TABLE Reports (
    ReportID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Type ENUM('Sales', 'Inventory', 'Customer') NOT NULL,
    GeneratedBy INT NOT NULL,
    GeneratedAtDate DATE,
    Time TIME,
    FOREIGN KEY (GeneratedBy) REFERENCES Users(UserID)
);

LOAD DATA INFILE 'E:\\Data\\Reporting and Analytics\\Reports.csv'
INTO TABLE Reports
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE ReportData (
    ReportDataID INT AUTO_INCREMENT PRIMARY KEY,
    ReportID INT NOT NULL,
    DataKey VARCHAR(255) NOT NULL,
    DataValue VARCHAR(255),
    FOREIGN KEY (ReportID) REFERENCES Reports(ReportID)
);

LOAD DATA INFILE 'E:\\Data\\Reporting and Analytics\\Report_data.csv'
INTO TABLE ReportData
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 7. Multi-location Support
CREATE TABLE Locations (
    LocationID INT AUTO_INCREMENT PRIMARY KEY,
    LocationName VARCHAR(255) NOT NULL,
    Address VARCHAR(255) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Multi-location Support\\Locations.csv'
INTO TABLE Locations
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
CREATE TABLE InventoryLocations (
    InventoryLocationID INT AUTO_INCREMENT PRIMARY KEY,
    LocationID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID)
);
LOAD DATA INFILE 'E:\\Data\\Multi-location Support\\Inventory_locations.csv'
INTO TABLE InventoryLocations
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- 8. Mobile Access
CREATE TABLE MobileDevices (
    DeviceID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    DeviceType VARCHAR(50) NOT NULL,
    DeviceToken VARCHAR(255) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);
LOAD DATA INFILE 'E:\\Data\\Mobile_access\\Mobile_Devices.csv'
INTO TABLE MobileDevices
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
CREATE TABLE MobileAccessLogs (
    MobileAccessID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    DeviceID INT,
    LocationID INT,
    AccessTime DATETIME NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (DeviceID) REFERENCES MobileDevices(DeviceID),
    FOREIGN KEY (LocationID) REFERENCES Locations(LocationID)
);
LOAD DATA INFILE 'E:\\Data\\Mobile_access\\Mobile_Access_logs.csv'
INTO TABLE MobileAccessLogs
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- 9. Integration with ERP
CREATE TABLE ERPSystems (
    ERPSystemID INT AUTO_INCREMENT PRIMARY KEY,
    ERPSystemName VARCHAR(255) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Integration with ERP\\ERPSystems.csv'
INTO TABLE ERPSystems
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
CREATE TABLE ERPIntegrations (
    ERPIntegrationID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ERPSystemID INT,
    IntegrationDate DATE NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (ERPSystemID) REFERENCES ERPSystems(ERPSystemID)
);
LOAD DATA INFILE 'E:\\Data\\Integration with ERP\\ERPIntegrations.csv'
INTO TABLE ERPIntegrations
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
-- 10. Real-time Updates
CREATE TABLE RealTimeInventory (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    StockQuantity INT NOT NULL,
    LastUpdated DATE, 
    Time TIME
);
LOAD DATA INFILE 'E:\\Data\\Real-time Updates\\RealTimeInventory.csv'
INTO TABLE  RealTimeInventory
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;
CREATE TABLE OrderStatuses (
  OrderStatusID INT AUTO_INCREMENT PRIMARY KEY,
  StatusName VARCHAR(50) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Real-time Updates\\OrderStatuses.csv'
INTO TABLE  OrderStatuses
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE RealTimeOrders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    OrderStatusID INT,
    CreatedAtDate DATE,
    CreatedAtTime TIME,
    UpdatedAtDate DATE,
    UpdatedAtTime TIME,
    FOREIGN KEY (ProductID) REFERENCES RealTimeInventory(ProductID),
    FOREIGN KEY (OrderStatusID) REFERENCES OrderStatuses(OrderStatusID)
);
LOAD DATA INFILE 'E:\\Data\\Real-time Updates\\Real_time_order.csv'
INTO TABLE RealTimeOrders
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE ShipmentStatuses (
  ShipmentStatusID INT AUTO_INCREMENT PRIMARY KEY,
  StatusName VARCHAR(50) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Real-time Updates\\Shipment_status.csv'
INTO TABLE ShipmentStatuses
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Shipments (
    ShipmentID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    ShipmentStatusID INT,
    ShipmentDate DATE,
    ShipmentTime TIME,
    FOREIGN KEY (OrderID) REFERENCES RealTimeOrders(OrderID),
    FOREIGN KEY (ShipmentStatusID) REFERENCES ShipmentStatuses(ShipmentStatusID)
);
LOAD DATA INFILE 'E:\\Data\\Real-time Updates\\Shipments.csv'
INTO TABLE Shipments
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 11. Audit Trail
CREATE TABLE AuditTrails (
    AuditID INT AUTO_INCREMENT PRIMARY KEY,
    TableName VARCHAR(255) NOT NULL,
    ActionType ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
    ChangedData TEXT,
    ChangedBy VARCHAR(255),
    ChangedAtDate DATE,
    ChangedAtTime TIME
);
LOAD DATA INFILE 'E:\\Data\\Audit_Trail\\AuditTrails.csv'
INTO TABLE AuditTrails
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- 12. Notifications
CREATE TABLE NotificationStatuses (
  NotificationStatusID INT AUTO_INCREMENT PRIMARY KEY,
  StatusName VARCHAR(50) NOT NULL
);
LOAD DATA INFILE 'E:\\Data\\Notifications\\Notification_status.csv'
INTO TABLE NotificationStatuses
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

CREATE TABLE Notifications (
    NotificationID INT AUTO_INCREMENT PRIMARY KEY,
	NotificationStatusID INT,
    UserID INT NOT NULL,
    NotificationType VARCHAR(50) NOT NULL,
    NotificationMessage TEXT NOT NULL,
    CreatedAtDate DATE,
    CreatedAtTime TIME,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (NotificationStatusID) REFERENCES NotificationStatuses(NotificationStatusID)
);
LOAD DATA INFILE 'E:\\Data\\Notifications\\Notification1.csv'
INTO TABLE Notifications
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

