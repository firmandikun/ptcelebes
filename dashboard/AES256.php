<?php



function encryptFile($file, $key, $outputFile)
{
    // Generate a random initialization vector (IV)
    $iv = openssl_random_pseudo_bytes(16);

    // Encrypt the file
    $cipherText = openssl_encrypt(file_get_contents($file), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    // Prepend the IV to the encrypted data
    $encryptedData = $iv . $cipherText;

    // Write the encrypted data to the output file
    file_put_contents($outputFile, $encryptedData);
}

// Function to decrypt a file using AES-256 decryption
function decryptFile($file, $key, $outputFile)
{
    // Read the encrypted data from the file
    $encryptedData = file_get_contents($file);

    // Extract the IV from the encrypted data
    $iv = substr($encryptedData, 0, 16);

    // Extract the cipher text (encrypted data without IV)
    $cipherText = substr($encryptedData, 16);

    // Decrypt the data
    $plainText = openssl_decrypt($cipherText, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    // Write the decrypted data to the output file
    file_put_contents($outputFile, $plainText);
}
