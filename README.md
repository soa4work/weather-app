# Weather app

Test task to build s small weather app

### 

## Installation:
```console
git clone https://github.com/soa4work/weather-app.git
cd weather-app
cp .env.example .env
docker-compose up -d
docker-compose exec laravel.test bash
./vendor/bin/sail up
```

### Get current weather endpoint

- **Url**: /api/current/{lang}/
- **Method**: GET
- **Params**:
    - *city*: string; required only when longitude and latitude params are empty;   
    - *longitude* number - required only when city param is empty and when latitude is present;
    - *latitude*: number - required only when city param is empty and when longitude is present;
    - *units*: string - optional param	(standard, metric, imperial) . When you do not use the units params, 
      format is "metric" by default.

#### Examples: 

- `https://localhost/api/current/ru?city=tomsk`

response: 200
```json
{
    "description": "пасмурно",
    "temp": 26,
    "pressure": 1012,
    "humidity": 73,
    "wind": {
       "direction": "южный",
       "speed": 2
    }
}
```

- `https://localhost/api/current/ru?city=unknown`

response: 404
```json
{
    "message": "Не найдено."
}
```

- `https://localhost/api/current/ru?latitude=220&longitude=1`

response: 422
```json
{
    "message": "Значение поля latitude должно быть между -90 и 90.",
    "errors": {
        "latitude": [
            "Значение поля latitude должно быть между -90 и 90."
        ]
    }
}
```

## Tests

`./vendor/bin/sail up -d && ./vendor/bin/sail test`
