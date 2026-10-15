-- ==============================================================================
-- SCRIPT SQL: Limpeza Completa de Produtos, Estoque e Compras de Teste
-- Empresa Alvo: VILLA RIUS FOOD LTDA (ID: 48)
-- ==============================================================================

SET @empresa_id = 48;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Limpar Estoque e Movimentações
DELETE FROM estoques 
WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);

DELETE FROM movimentacao_produtos 
WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);

-- 2. Limpar Localizações e Fornecedores dos Produtos
DELETE FROM produto_localizacaos 
WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);

DELETE FROM produto_fornecedors 
WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);

-- 3. Limpar Tabelas Auxiliares de Produtos (Variações, Composições, etc.)
DELETE FROM galeria_produtos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_variacaos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_composicaos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_ingredientes WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_adicionals WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_combos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_pizza_valors WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM produto_unicos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);
DELETE FROM item_lista_precos WHERE produto_id IN (SELECT id FROM produtos WHERE empresa_id = @empresa_id);

-- 4. Limpar Compras de Entrada (para não duplicar na lista de compras ao reimportar o mesmo XML)
DELETE FROM fatura_nves WHERE nfe_id IN (SELECT id FROM nves WHERE empresa_id = @empresa_id AND tpNF = 0);
DELETE FROM item_nves WHERE nfe_id IN (SELECT id FROM nves WHERE empresa_id = @empresa_id AND tpNF = 0);
DELETE FROM conta_pagars WHERE empresa_id = @empresa_id AND nfe_id IS NOT NULL;
DELETE FROM nves WHERE empresa_id = @empresa_id AND tpNF = 0;

-- 5. Excluir os Produtos
DELETE FROM produtos WHERE empresa_id = @empresa_id;

SET FOREIGN_KEY_CHECKS = 1;

SELECT CONCAT('Limpeza concluída com sucesso para a empresa ID: ', @empresa_id) AS Resultado;
