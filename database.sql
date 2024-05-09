create table if not exists date_dimension
(
    id                  binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    year                int        not null comment 'A full numeric representation of a year, 4 digits',
    month               int        not null comment 'Day of the month without leading zeros; 1 to 12',
    day                 int        not null comment 'Day of the month without leading zeros; 1 to 31',
    quarter             int        not null comment 'Calendar quarter; 1, 2, 3 or 4',
    week_number         int        not null comment 'ISO-8601 week number of year, weeks starting on Monday',
    day_number_of_week  int        not null comment 'ISO-8601 numeric representation of the day of the week; 1 (for Monday) to 7 (for Sunday)',
    day_number_of_year  int        not null comment 'The day of the year (starting from 0); 0 through 365',
    leap_year           tinyint(1) not null comment 'Whether it''s a leap year or not',
    week_numbering_year int        not null comment 'ISO-8601 week-numbering year.',
    unix_time           bigint     not null comment 'Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)',
    date                date       not null comment '(DC2Type:date_immutable)'
    )
    collate = utf8mb4_unicode_ci;

create index date
    on date_dimension (date);

create table if not exists health
(
    id        binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    timestamp datetime   not null comment '(DC2Type:datetime_immutable)'
    )
    collate = utf8mb4_unicode_ci;

create table if not exists messenger_messages
(
    id           bigint auto_increment
    primary key,
    body         longtext     not null,
    headers      longtext     not null,
    queue_name   varchar(190) not null,
    created_at   datetime     not null comment '(DC2Type:datetime_immutable)',
    available_at datetime     not null comment '(DC2Type:datetime_immutable)',
    delivered_at datetime     null comment '(DC2Type:datetime_immutable)'
    )
    collate = utf8mb4_unicode_ci;

create index IDX_75EA56E016BA31DB
    on messenger_messages (delivered_at);

create index IDX_75EA56E0E3BD61CE
    on messenger_messages (available_at);

create index IDX_75EA56E0FB7336F0
    on messenger_messages (queue_name);

create table if not exists scheduled_command
(
    id                  int auto_increment
    primary key,
    version             int default 1 not null,
    created_at          datetime      null,
    name                varchar(150)  not null,
    command             varchar(200)  not null,
    arguments           longtext      null,
    cron_expression     varchar(200)  null,
    last_execution      datetime      null,
    last_return_code    int           null,
    log_file            varchar(150)  null,
    priority            int           not null,
    execute_immediately tinyint(1)    not null,
    disabled            tinyint(1)    not null,
    locked              tinyint(1)    not null,
    constraint UNIQ_EA0DBC905E237E06
    unique (name)
    )
    collate = utf8mb4_unicode_ci;

create table if not exists user
(
    id                   binary(16)                         not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    created_by_id        binary(16)                         null comment '(DC2Type:uuid_binary_ordered_time)',
    updated_by_id        binary(16)                         null comment '(DC2Type:uuid_binary_ordered_time)',
    username             varchar(255)                       not null,
    first_name           varchar(255)                       not null,
    last_name            varchar(255)                       not null,
    email                varchar(255)                       not null,
    birthday             datetime                           null,
    image                varchar(255)                       null,
    sex                  varchar(255)                       not null,
    social_media         json                               null,
    language             enum ('en', 'ru', 'ua', 'fi')      not null comment 'User language for translations',
    locale               enum ('en', 'ru', 'ua', 'fi')      not null comment 'User locale for number, time, date, etc. formatting.',
    timezone             varchar(255) default 'Europe/Kyiv' not null comment 'User timezone which should be used to display time, date, etc.',
    password             varchar(255)                       not null comment 'Hashed password',
    created_at           datetime                           null comment '(DC2Type:datetime_immutable)',
    updated_at           datetime                           null comment '(DC2Type:datetime_immutable)',
    address_country      varchar(255)                       not null,
    address_city         varchar(255)                       not null,
    address_postcode     varchar(255)                       not null,
    address_street       varchar(255)                       not null,
    address_house_number varchar(255)                       not null,
    constraint uq_email
    unique (email),
    constraint uq_username
    unique (username),
    constraint FK_8D93D649896DBBDE
    foreign key (updated_by_id) references user (id)
    on delete set null,
    constraint FK_8D93D649B03A8386
    foreign key (created_by_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create table if not exists api_key
(
    id               binary(16)   not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    created_by_id    binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    updated_by_id    binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    token            varchar(255) not null comment 'Generated API key string for authentication',
    token_hash       varchar(255) null comment 'Token hash (when encrypted)',
    token_parameters json         null comment 'Token decrypt parameters (when encrypted)',
    description      longtext     not null,
    created_at       datetime     null comment '(DC2Type:datetime_immutable)',
    updated_at       datetime     null comment '(DC2Type:datetime_immutable)',
    constraint uq_token
    unique (token),
    constraint FK_C912ED9D896DBBDE
    foreign key (updated_by_id) references user (id)
    on delete set null,
    constraint FK_C912ED9DB03A8386
    foreign key (created_by_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create index IDX_C912ED9D896DBBDE
    on api_key (updated_by_id);

create index IDX_C912ED9DB03A8386
    on api_key (created_by_id);

create table if not exists log_login
(
    id                binary(16)                  not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    user_id           binary(16)                  null comment '(DC2Type:uuid_binary_ordered_time)',
    username          varchar(255)                not null,
    client_type       varchar(255)                null,
    client_name       varchar(255)                null,
    client_short_name varchar(255)                null,
    client_version    varchar(255)                null,
    client_engine     varchar(255)                null,
    os_name           varchar(255)                null,
    os_short_name     varchar(255)                null,
    os_version        varchar(255)                null,
    os_platform       varchar(255)                null,
    device_name       varchar(255)                null,
    brand_name        varchar(255)                null,
    model             varchar(255)                null,
    type              enum ('failure', 'success') not null,
    time              datetime                    not null comment '(DC2Type:datetime_immutable)',
    date              date                        not null comment '(DC2Type:date_immutable)',
    agent             longtext                    not null,
    http_host         varchar(255)                not null,
    client_ip         varchar(255)                not null,
    constraint FK_8A76204DA76ED395
    foreign key (user_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create index date
    on log_login (date);

create index user_id
    on log_login (user_id);

create table if not exists log_login_failure
(
    id        binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    user_id   binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)',
    timestamp datetime   not null comment '(DC2Type:datetime_immutable)',
    constraint FK_EDB4AF3A76ED395
    foreign key (user_id) references user (id)
    on delete cascade
    )
    collate = utf8mb4_unicode_ci;

create index user_id
    on log_login_failure (user_id);

create table if not exists log_request
(
    id                      binary(16)   not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    user_id                 binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    api_key_id              binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    status_code             int          not null,
    response_content_length int          not null,
    is_main_request         tinyint(1)   not null,
    time                    datetime     not null comment '(DC2Type:datetime_immutable)',
    date                    date         not null comment '(DC2Type:date_immutable)',
    agent                   longtext     not null,
    http_host               varchar(255) not null,
    client_ip               varchar(255) not null,
    headers                 json         not null,
    method                  varchar(255) not null,
    scheme                  varchar(5)   not null,
    base_path               varchar(255) not null,
    script                  varchar(255) not null,
    path                    varchar(255) null,
    query_string            longtext     null,
    uri                     longtext     not null,
    controller              varchar(255) null,
    content_type            varchar(255) null,
    content_type_short      varchar(255) null,
    is_xml_http_request     tinyint(1)   not null,
    action                  varchar(255) null,
    content                 longtext     null,
    parameters              json         not null,
    constraint FK_35AB7088BE312B3
    foreign key (api_key_id) references api_key (id)
    on delete set null,
    constraint FK_35AB708A76ED395
    foreign key (user_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create index api_key_id
    on log_request (api_key_id);

create index request_date
    on log_request (date);

create index user_id
    on log_request (user_id);

create table if not exists role
(
    role          varchar(255) not null
    primary key,
    created_by_id binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    updated_by_id binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    description   longtext     not null,
    created_at    datetime     null comment '(DC2Type:datetime_immutable)',
    updated_at    datetime     null comment '(DC2Type:datetime_immutable)',
    constraint uq_role
    unique (role),
    constraint FK_57698A6A896DBBDE
    foreign key (updated_by_id) references user (id)
    on delete set null,
    constraint FK_57698A6AB03A8386
    foreign key (created_by_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create index IDX_57698A6A896DBBDE
    on role (updated_by_id);

create index IDX_57698A6AB03A8386
    on role (created_by_id);

create index IDX_8D93D649896DBBDE
    on user (updated_by_id);

create index IDX_8D93D649B03A8386
    on user (created_by_id);

create table if not exists user_group
(
    id            binary(16)   not null comment '(DC2Type:uuid_binary_ordered_time)'
    primary key,
    role          varchar(255) null,
    created_by_id binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    updated_by_id binary(16)   null comment '(DC2Type:uuid_binary_ordered_time)',
    name          varchar(255) not null,
    created_at    datetime     null comment '(DC2Type:datetime_immutable)',
    updated_at    datetime     null comment '(DC2Type:datetime_immutable)',
    constraint FK_8F02BF9D57698A6A
    foreign key (role) references role (role)
    on delete cascade,
    constraint FK_8F02BF9D896DBBDE
    foreign key (updated_by_id) references user (id)
    on delete set null,
    constraint FK_8F02BF9DB03A8386
    foreign key (created_by_id) references user (id)
    on delete set null
    )
    collate = utf8mb4_unicode_ci;

create table if not exists api_key_has_user_group
(
    api_key_id    binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)',
    user_group_id binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)',
    primary key (api_key_id, user_group_id),
    constraint FK_E2D0E7F91ED93D47
    foreign key (user_group_id) references user_group (id)
    on delete cascade,
    constraint FK_E2D0E7F98BE312B3
    foreign key (api_key_id) references api_key (id)
    on delete cascade
    )
    collate = utf8mb4_unicode_ci;

create index IDX_E2D0E7F91ED93D47
    on api_key_has_user_group (user_group_id);

create index IDX_E2D0E7F98BE312B3
    on api_key_has_user_group (api_key_id);

create index IDX_8F02BF9D57698A6A
    on user_group (role);

create index IDX_8F02BF9D896DBBDE
    on user_group (updated_by_id);

create index IDX_8F02BF9DB03A8386
    on user_group (created_by_id);

create table if not exists user_has_user_group
(
    user_id       binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)',
    user_group_id binary(16) not null comment '(DC2Type:uuid_binary_ordered_time)',
    primary key (user_id, user_group_id),
    constraint FK_2C599571ED93D47
    foreign key (user_group_id) references user_group (id)
    on delete cascade,
    constraint FK_2C59957A76ED395
    foreign key (user_id) references user (id)
    on delete cascade
    )
    collate = utf8mb4_unicode_ci;

create index IDX_2C599571ED93D47
    on user_has_user_group (user_group_id);

create index IDX_2C59957A76ED395
    on user_has_user_group (user_id);

