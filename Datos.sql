USE Buff;
GO

-- 1. USUARIOS 
INSERT INTO USUARIOS (NOMBRE, ROL, CORREO, TELEFONO, USUARIO, PASSWORD) VALUES
('Roberto', 'Planificador Senior', 'roberto.p@buff.com', '555-0101','roberto','1234'),
('Ana', 'Gerente Log�stica', 'ana.g@buff.com', '555-0102','ana','1234'),        
('Carlos', 'Comprador', 'carlos.c@buff.com', '555-0103','carlos','1234'),          
('Mar�a', 'Analista de Datos', 'maria.d@buff.com', '555-0104','maria','1234'),    
('Jorge', 'Experto Almac�n', 'jorge.e@buff.com', '555-0105','jorge','1234');      

-- 2. CLASIFICACIONES
INSERT INTO CLASIFICACIONES (NOMBRE, DESCRIPCION, NIVEL_SERVICIO) VALUES
('A', 'Sensores y componentes electr�nicos', 'Alto'),   
('B', 'Partes de motor y transmisi�n', 'Alto'),         
('C', 'Herramientas de ensamble', 'Medio'),             
('D', 'Torniller�a y fijaciones', 'Bajo'),              
('Insumos', 'Material de empaque y consumibles', 'Bajo');

-- 3. PRODUCTOS 
INSERT INTO PRODUCTOS 
(NOMBRE, VALOR_UNITARIO, ESTATUS, IDCLASIFICACION) 
VALUES
('Sensor O2', 50000.00, 'ACTIVO', 1),
('Tornillo M8', 2.00, 'ACTIVO', 4),
('Sensor ABS', 1200.00, 'ACTIVO', 1),
('Tornillo M10x50', 5.00, 'ACTIVO', 4),
('Juntas de culata', 850.00, 'ACTIVO', 2);

-- 4. INVENTARIO 
INSERT INTO INVENTARIO (STOCK, STOCK_MINIMO, IDPRODUCTO) VALUES
(65, 100, 1),    -- Sensor O2: Faltante cr�tico (Buffer insuficiente) 
(9200, 5000, 2), -- Tornillo M8: Exceso de stock (Capital inmovilizado) 
(15, 20, 3),     -- Sensor ABS: Por debajo del m�nimo 
(10000, 3500, 4),-- Tornillo M10: Exceso masivo detectado 
(500, 800, 5);   -- Juntas: Necesita ajuste al alza 

-- 5. MOVIMIENTOS 
INSERT INTO MOVIMIENTOS (TIPO_MOVIMIENTO, CANTIDAD, FECHA, IDUSUARIO, IDPRODUCTO) VALUES
('SALIDA', 35, '2024-03-15 10:00', 2, 1), -- Caus� el faltante cr�tico 
('ENTRADA', 10000, '2024-03-05 09:00', 3, 2), -- Compra sin optimizaci�n 
('SALIDA', 5, '2024-03-20 11:00', 5, 3),
('ENTRADA', 15, '2024-03-01 08:00', 3, 1),
('ENTRADA', 200, '2024-03-10 08:00', 4, 5);

-- 6. HISTORIAL
INSERT INTO HISTORIAL (CAMPO_MODIFICADO, VALOR_ANTERIOR, VALOR_NUEVO, FECHA, IDPRODUCTO) VALUES
('STOCK_MINIMO', '100', '135', '2024-03-20 10:00', 1), -- Ajuste reactivo 
('VALOR_UNITARIO', '48000', '50000', '2024-02-15 08:00', 1),
('ESTATUS', 'INACTIVO', 'ACTIVO', '2024-01-01 08:00', 3),
('STOCK_MINIMO', '5000', '3500', '2024-03-25 09:00', 4), -- Reducci�n de capital 
('LEAD_TIME', '5 d�as', '8 d�as', '2024-03-01 09:00', 1); -- Registro de falla proveedor 

-- 7. ALERTAS 
INSERT INTO ALERTAS (MENSAJE, NIVEL_ALERTA, FECHA) VALUES
('RIESGO_PENALIZACION_FALTANTE', 'CRITICO', GETDATE()),  
('VARIACION_LEAD_TIME_PROVEEDOR', 'MEDIO', GETDATE()),  
('EXCESO_CAPITAL_INMOVILIZADO', 'BAJO', GETDATE()),    
('TENDENCIA_DEMANDA_ATIPICA', 'MEDIO', GETDATE()),      
('STOCK_BAJO_MINIMO_SEGURIDAD', 'CRITICO', GETDATE());   