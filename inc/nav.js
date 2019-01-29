nav = {
    
    location: null,
    
    state: {
        mylinks: { here:false, block:null, callback: undefined },
        add_link: { here:false, block:null, callback: undefined },
        change_link: { here:false, block:null, callback: undefined },
        person: { here:false, block:null, callback: undefined },
        statistics: { here:false, block:null, callback: undefined }
    },
    
    go: function(el, e){
        
        e.preventDefault();
        
        // получаем полную ссылку
        var all_href = el.getAttribute("href");
        let args = "";
        // разбиваем ссылку, если есть аргументы
        let reg = all_href.match(/([^\s\?]+)/gm);
        
        if(reg != null){
            var href = reg[0];
            
            if(all_href.match(/([^\s]+)\?([^\s]+)/)){
                args = reg[1];
            }
        }
        
        
        if(href in this.state){
            
            // скрываем все блоки
            for(let key in this.state){
                this.state[key].block.setAttribute("style", "display: none");
                this.state[key].block.style.display = "none";
            }
            
            
            // отображаем активную область
            
            let nav_children = document.getElementsByTagName("nav")[0].children[0].children;
            
            for(let i = 0; i < 4; i++){
                nav_children[i].children[0].classList.remove("active_menu");
            }
            
            el.children[0].classList.add("active_menu");
            
            // показываем нужный; догружаем, если требуется
            if(this.state[href].here && href != "mylinks" && href != "statistics" && !(href == 'change_link' && args != "")){
                this.state[href].block.style.display = "block";
            }else{
                this.state[href].block.innerHTML = "<load>Загрузка...</load>";
                this.state[href].block.style.display = "block";
                this.load({ href:href, data: args }, nav.state[href].callback);
            }
            
            history.pushState({href:all_href}, '', href+((args)?"?"+args:""));
            this.location = href;
            
        }else{
            return;
        }
        
    },
    
    load: function(params, callback){
        
        let obj = this;
        
        $.ajax({
            type: 'POST',
            url: "nav/"+params.href+".php",
            data: params.data,
            success: function(data){
                obj.state[params.href].block.innerHTML = data;
                obj.state[params.href].here = true;
                
                if(callback != undefined) callback(); 
            },
            error: function(xhr,str){
                console.log('Error: '+xhr.responseCode);
            }
        })
    },
    
    popstate: function(event){

        // получаем полную ссылку
        var all_href = event.state.href;
        let args = "";

        // разбиваем ссылку, если есть аргументы
        let reg = all_href.match(/([^\s\?]+)/gm);
        
        if(reg != null){
            var href = reg[0];
            
            if(all_href.match(/([^\s]+)\?([^\s]+)/)){
                args = reg[1];
            }
        }
        
        
        
        nav.location = href;
        
        if(!(nav.location in nav.state)){
            console.log('error: incorrect history href');
            return;
        }
            
        for(let key in nav.state){
                
            nav.state[key].block.setAttribute("style", "display: none");
            nav.state[key].block.style.display = "none";
                
            if(key == nav.location){
                   
                let href = key;
                
                // здесь код из nav.go с поиском el
                
                let el = null;

                // отображаем активную область и ищем нужную ссылку
            
                let nav_children = document.getElementsByTagName('nav')[0].children[0].children;
                    
                for(let i = 0; i < 4; i++){
                    
                    nav_children[i].children[0].classList.remove("active_menu");
                    
                    // поиск el
                    if(nav_children[i].getAttribute('href') == href){
                        el = nav_children[i];
                    }
                   
                }
                
                if(href != "statistics"){
                    
                    if(!el){
                        console.log('error: el is null in popstate');
                        return;
                    }
                        
                    el.children[0].classList.add("active_menu");
                    
                }
                    
                // показываем нужный; догружаем, если требуется
                if(nav.state[href].here && href != "mylinks" && href != "statistics" && (href != 'change_link' && args)){
                    nav.state[href].block.style.display = "block";
                }else{
                    nav.state[href].block.innerHTML = "<load>Загрузка...</load>";
                    nav.state[href].block.style.display = "block";
                    nav.load({ href:href, data: args }, nav.state[href].callback);
                }
                
                nav.location = href;
                
            }
        }
        
        
    },
    
    delete_link: function(id_link, e){
        
        e.preventDefault();
        
        $("#links_table_item_"+id_link).fadeOut(200);
        
        $.ajax({
            type: 'POST',
            url: "inc/delete_link.php",
            data: {id_link:id_link},
            success: function(data){
                
                switch(data){
                    case 'success':
                        document.querySelector("#links_table_item_"+id_link).remove();
                        alertb("Ссылка успешно удалена!", 3000);
                        return;
                    break;
                    case 'error: time': alertb("Слишком много запросов. Пожалуйста, повторите через несколько секунд"); break;
                    case 'error: access': alertb("Неизвестная ошибка"); break;
                    case 'error: singin': alertb("Требуется авторизация"); break;
                    case 'error: empty': alertb("Подано пустое значение в качестве аргумента"); break;
                    case 'error: del link': alertb("Ошибка при удалении ссылки. Пожалуйста, обратитесь в службу поддержки", 5000); break;
                    case 'error: del rel': alertb("Ошибка при удалении связей с ссылкой. Пожалуйста, обратитесь в службу поддержки", 5000); break;
                    case 'error: exist': alertb("Такой ссылки не существует либо Вам отказано в доступе к ней", 5000); break;
                    default: alertb(data, 10000); break;
                }
                
                $("#links_table_item_"+id_link).fadeIn(200);
            },
            error: function(xhr,str){
                console.log('Error: '+xhr.responseCode);
            }
        })
    },
    
    add_link: function(e){
        
        e.preventDefault();
        
        let but = document.querySelector("#form_add_link_but");
        but.setAttribute("value", "Загрузка...");
        
        let d = $('#form_add_link').serialize();
        
        $.ajax({
            type: 'POST',
            data: d,
            url: "inc/add_link_inc.php",
            success: function(data){
                switch(data){
                    case 'success': alertb("Ссылка успешно добавлена"); document.querySelector("#form_add_link").reset(); break;
                    case 'error: access': alertb("Ошбика доступа, авторизируйтесь, пожалуйста"); break;
                    case 'error: singin': alertb("Необходимо авторизироваться"); break;
                    case 'error: empty': alertb("Заполните форму"); break;
                    case 'error: update': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: select': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: insert': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: time': alertb("Слишкол частые запросы"); break;
                    default: alertb(data); break;
                }
                
                but.setAttribute("value", "Добавить ссылку");
                
            },
            error: function(xhr,str){
                console.log('Error: '+xhr.responseCode);
            }
        })
    },
    
    change_link: function(e){
        
        e.preventDefault();
        
        let but = document.querySelector("#form_change_link_but");
        but.setAttribute("value", "Загрузка...");
        
        let d = $('#form_change_link').serialize();
        
        $.ajax({
            type: 'POST',
            data: d,
            url: "inc/change_link_inc.php",
            success: function(data){
                switch(data){
                    case 'success': alertb("Ссылка успешно добавлена"); 
                        document.querySelector("#form_change_link").reset();
                        nav.state.change_link.here = false;
                        nav.go(document.querySelector("#a_mylinks"), event); 
                    break;
                    case 'error: access': alertb("Ошбика доступа, авторизируйтесь, пожалуйста"); break;
                    case 'error: singin': alertb("Необходимо авторизироваться"); break;
                    case 'error: empty': alertb("Заполните форму"); break;
                    case 'error: update': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: select': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: time': alertb("Слишкол частые запросы"); break;
                    default: alertb(data); break;
                }
                
                but.setAttribute("value", "Изменить ссылку");
                
            },
            error: function(xhr,str){
                console.log('Error: '+xhr.responseCode);
            }
        })
    },
    
    change_pass: function(e){
        
        e.preventDefault();
        
        let but = document.querySelector("#change_pass_butt");
        but.setAttribute("value", "Загрузка...");

        
        let d = $('#change_pass').serialize();
        
        $.ajax({
            type: 'POST',
            data: d,
            url: "inc/change_pass_inc.php",
            success: function(data){
                
                switch(data){
                    case 'success': alertb("Пароль успешно сменён!"); 
                        document.querySelector("#change_pass").reset();
                    break;
                    case 'error: access': alertb("Ошбика доступа, авторизируйтесь, пожалуйста"); break;
                    case 'error: singin': alertb("Необходимо авторизироваться"); break;
                    case 'error: empty': alertb("Заполните форму"); break;
                    case 'error: update': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: select': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: insert': alertb("Ошибка при работе с БД, обратитесь в службу поддержки"); break;
                    case 'error: pass': alertb("Неверный пароль!"); break;
                    case 'error: repeat': alertb("Пароли не совпадают"); break;
                    case 'error: letters': alertb("Нельзя использовать < > ' \""); break;
                    case 'error: time': alertb("Слишкол частые запросы"); break;
                    default: alertb(data); break;
                }
                
                but.setAttribute("value", "Сменить пароль");
                
            },
            error: function(xhr,str){
                console.log('Error: '+xhr.responseCode);
            }
        })
    },
    
    init: function(){
        
        for(let key in this.state){
            this.state[key].block = document.querySelector("#"+key);
        }
        
        window.onpopstate = this.popstate;
        
    }
}

window.onload = function(){
    nav.init();
}









// set callbacks

// add_link with callback
nav.state.add_link.callback = function(){
    
    document.querySelector("#form_add_link_but").onclick = nav.add_link;
    
    document.querySelector("#form_add_link_inp").onkeydown = function(e){
        
        if(e.keyCode == 13){
            e.preventDefault();
            nav.add_link(e);
        }
    }
};

// change_link with callback
nav.state.change_link.callback = function(){
    
    document.querySelector("#form_add_link_but").onclick = nav.change_link;
    
    document.querySelector("#form_change_link_but").onkeydown = function(e){
        
        if(e.keyCode == 13){
            e.preventDefault();
            nav.change_link(e);
        }
    }
};


// person with callback
nav.state.person.callback = function(){
    
    document.querySelector("#change_pass_butt").onclick = nav.change_pass;
    
    document.querySelector("#change_pass_o_p").onkeydown = function(e){
        
        if(e.keyCode == 13){
            e.preventDefault();
            nav.change_pass(e);
        }
    }
    document.querySelector("#change_pass_p").onkeydown = function(e){
        
        if(e.keyCode == 13){
            e.preventDefault();
            nav.change_pass(e);
        }
    }
    document.querySelector("#change_pass_p_r").onkeydown = function(e){
        
        if(e.keyCode == 13){
            e.preventDefault();
            nav.change_pass(e);
        }
    }
};



// show notice

function alertb(text, time){
    
    let div = document.createElement('div');
    
    div.setAttribute("class", "alert");
    let id = "alertb"+Math.round(Math.random()*1000000);
    div.setAttribute("id", id);
    div.style.top = (55 + window.scrollY) + "px";
    
    div.innerHTML = text;
    
    if(time === undefined){
        time = 4000;
    }
    
    let timerId = setTimeout("document.querySelector('#"+id+"').remove()", time);
    
    div.onclick = function(){
        this.remove();
        clearTimeout(timerId);
    }
    
    document.body.appendChild(div);
    $(div).fadeIn(200);
    
}