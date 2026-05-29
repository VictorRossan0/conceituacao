param(
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]]$ArtisanCommand
)

docker compose exec backend php artisan @ArtisanCommand