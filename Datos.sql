USE Buff;
GO

-- 1. USUARIOS 
INSERT INTO USUARIOS (NOMBRE, ROL, CORREO, TELEFONO) VALUES
('Roberto', 'Planificador Senior', 'roberto.p@buff.com', '555-0101'),
('Ana', 'Gerente Logística', 'ana.g@buff.com', '555-0102'),        
('Carlos', 'Comprador', 'carlos.c@buff.com', '555-0103'),          
('María', 'Analista de Datos', 'maria.d@buff.com', '555-0104'),    
('Jorge', 'Experto Almacén', 'jorge.e@buff.com', '555-0105');      

-- 2. CLASIFICACIONES
INSERT INTO CLASIFICACIONES (NOMBRE, DESCRIPCION, NIVEL_SERVICIO) VALUES
('A', 'Sensores y componentes electrónicos', 'Alto'),   
('B', 'Partes de motor y transmisión', 'Alto'),         
('C', 'Herramientas de ensamble', 'Medio'),             
('D', 'Tornillería y fijaciones', 'Bajo'),              
('Insumos', 'Material de empaque y consumibles', 'Bajo');

-- 3. PRODUCTOS 
INSERT INTO PRODUCTOS (VALOR_UNITARIO, ESTATUS, IDCLASIFICACION) VALUES
(50000.00, 'ACTIVO', 1), -- Sensor O2 
(2.00, 'ACTIVO', 4),     -- Tornillo M8 
(1200.00, 'ACTIVO', 1),  -- Sensor ABS 
(5.00, 'ACTIVO', 4),     -- Tornillo M10x50 
(850.00, 'ACTIVO', 2);   -- Juntas de culata

-- 4. INVENTARIO 
INSERT INTO INVENTARIO (STOCK, STOCK_MINIMO, IDPRODUCTO) VALUES
(65, 100, 1),    -- Sensor O2: Faltante crítico (Buffer insuficiente) 
(9200, 5000, 2), -- Tornillo M8: Exceso de stock (Capital inmovilizado) 
(15, 20, 3),     -- Sensor ABS: Por debajo del mínimo 
(10000, 3500, 4),-- Tornillo M10: Exceso masivo detectado 
(500, 800, 5);   -- Juntas: Necesita ajuste al alza 

-- 5. MOVIMIENTOS 
INSERT INTO MOVIMIENTOS (TIPO_MOVIMIENTO, CANTIDAD, FECHA, IDUSUARIO, IDPRODUCTO) VALUES
('SALIDA', 35, '2024-03-15 10:00', 2, 1), -- Causó el faltante crítico 
('ENTRADA', 10000, '2024-03-05 09:00', 3, 2), -- Compra sin optimización 
('SALIDA', 5, '2024-03-20 11:00', 5, 3),
('ENTRADA', 15, '2024-03-01 08:00', 3, 1),
('ENTRADA', 200, '2024-03-10 08:00', 4, 5);

-- 6. HISTORIAL
INSERT INTO HISTORIAL (CAMPO_MODIFICADO, VALOR_ANTERIOR, VALOR_NUEVO, FECHA, IDPRODUCTO) VALUES
('STOCK_MINIMO', '100', '135', '2024-03-20 10:00', 1), -- Ajuste reactivo 
('VALOR_UNITARIO', '48000', '50000', '2024-02-15 08:00', 1),
('ESTATUS', 'INACTIVO', 'ACTIVO', '2024-01-01 08:00', 3),
('STOCK_MINIMO', '5000', '3500', '2024-03-25 09:00', 4), -- Reducción de capital 
('LEAD_TIME', '5 días', '8 días', '2024-03-01 09:00', 1); -- Registro de falla proveedor 

-- 7. ALERTAS 
INSERT INTO ALERTAS (MENSAJE, NIVEL_ALERTA, FECHA) VALUES
('RIESGO_PENALIZACION_FALTANTE', 'CRITICO', GETDATE()),  
('VARIACION_LEAD_TIME_PROVEEDOR', 'MEDIO', GETDATE()),  
('EXCESO_CAPITAL_INMOVILIZADO', 'BAJO', GETDATE()),    
('TENDENCIA_DEMANDA_ATIPICA', 'MEDIO', GETDATE()),      
('STOCK_BAJO_MINIMO_SEGURIDAD', 'CRITICO', GETDATE());   