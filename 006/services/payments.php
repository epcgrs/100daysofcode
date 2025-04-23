<?php

/**
 * Cria um novo pagamento no banco de dados.
 *
 * @param string $paymentHash
 * @param int $amount
 * @param string $received
 * @return bool
 */
function createPayment($paymentHash, $amount, $received = 0) {
    $pdo = getConnection();

    try {
        $stmt = $pdo->prepare("INSERT INTO payments (payment_hash, amount, received) 
                               VALUES (:payment_hash, :amount, :received)");
        $stmt->bindParam(':payment_hash', $paymentHash);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':received', $received);
        
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao criar pagamento: " . $e->getMessage();
        return false;
    }
}

/**
 * Atualiza o status de um pagamento no banco de dados.
 *
 * @param string $hashId
 * @param string $status
 * @param string|null $paymentDate
 * @return bool
 */
function updatePaymentStatus($hashId, $received, $paymentDate = null) {
    $pdo = getConnection();

    try {
        $stmt = $pdo->prepare("UPDATE payments SET payment_date = :payment_date, received = :received 
                               WHERE payment_hash = :hashId");
        $stmt->bindParam(':payment_date', $paymentDate);
        $stmt->bindParam(':received', $received);
        $stmt->bindParam(':hashId', $hashId);

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao atualizar pagamento: " . $e->getMessage();
        return false;
    }
}