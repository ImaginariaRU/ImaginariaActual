# ShowVotes 

Показывает список голосовавших за топик

# Автор

Stanislav Nevolin, https://github.com/snevolin/showvotes

# Использует библиотеку 

poshytip_tooltip.js

```
Poshy Tip jQuery plugin v1.1+
http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips/
Copyright 2010-2011, Vasil Dinkov, http://vadikom.com/
```

# Issues
 
В classes/modules/vote/Vote.class.php в методе GetTopicVoters() используется
неоптимальный метод получения оценок.

Делается 3 запроса с разными WHERE vote_direction , тогда как оптимально сделать
ОДИН запрос, выбирая ВСЕ vote_direction и рассортировывая их по подмассивам на стороне PHP

