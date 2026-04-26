
CREATE TABLE guestbook (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    status      ENUM('good', 'deleted') DEFAULT 'good',
    name        VARCHAR(50) NOT NULL,
    message     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address  VARCHAR(45) NOT NULL
);


CREATE TABLE accounts (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) NOT NULL UNIQUE,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,

    role            ENUM('user', 'admin') DEFAULT 'user',

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE api_keys (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    account_id      INT UNSIGNED NOT NULL,

    api_key         CHAR(64) NOT NULL UNIQUE,
    name            VARCHAR(100) DEFAULT NULL,

    is_revoked      BOOLEAN DEFAULT FALSE,
    expires_at      TIMESTAMP NULL,

    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (account_id)
        REFERENCES accounts(id)
        ON DELETE CASCADE
);

CREATE TABLE api_key_usage (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    api_key_id  INT UNSIGNED NOT NULL,
    used_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    used_type   VARCHAR(20) NOT NULL,
    ip_address  VARCHAR(45),

    FOREIGN KEY (api_key_id)
        REFERENCES api_keys(id)
        ON DELETE CASCADE
);

CREATE TABLE counters (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50) NOT NULL UNIQUE,
    value       BIGINT UNSIGNED NOT NULL DEFAULT 0
);