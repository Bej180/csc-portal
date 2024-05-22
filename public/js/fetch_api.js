export default class fetch_api {
  constructor(page, data, init) {
    const success = init.success || (() => {});
    const error = init.error || (() => {});
    Object.keys(init).forEach((key, i) => {
      if (['success', 'error','method'].includes(key)) {
        init.splice(i, 1);
      }
    })
    console.log(init,'das')

    init = {
      method: 'POST',
      cache: 'no-cache',
      headers: {'Content-Type': 'application/json', 'Accept':'applicatin/json'},
      ...init || {}
    };

    let url = `/api${page}`;

    if ('type' in init) {
      init.method = init.type;
      delete init.type;
    }




    if (['GET','HEAD'].includes(init.method) && data) {
      
      const newUrl = new URL(url, fetchApi.baseURL);
      const params = newUrl.searchParams;
    
      for (const query in data) {
        params.append(query, data[query]);
      }
      url = newUrl.toString();
    } else {
      init.body = JSON.stringify(data)
    }

    return new Promise((resolve, reject) => {


      if(typeof localStorage !== 'undefined' && localStorage !== null && localStorage.getItem('access_token')) {
       init.headers.Authorization = `Bearer ${localStorage.getItem('access_token')}`;
      }
      
      fetch(url, init)
      .then(response => {

        if (!response.ok) {
          throw new Error('Failed to fetch response from server');
        }
        return response.json();
          
      })
      .then(data => {
        if ('access_token' in data) {
          localStorage.setItem('access_token', data.access_token);
        }
        if  ('success' in init && typeof init.success === 'function') {
          return success(data);
        }
        resolve(data);
      })
      .catch(error => {
        if  ('error' in init && typeof init.error === 'function') {
          return error(data);
        }
        reject(error);
      })
    });

  }

  static get(page, data, init) {
    init.method = 'GET';

    return new fetch_api(page, data, init);
    
  }

  static ajax(url, {type = 'GET', data = {}, dataType = 'json', success = ()=>{}, error = ()=>{}, init = {}, done = ()=>{}}) {
    try {
      init.method = type;
      const api = new fetch_api(url, data, init);
  
      api.then((data) => {
        console.log(data);
      }).catch(error).finally(done);
    } catch(e) {} finally {}

    
  }

  static post(page, data, init) {
    init.method = 'POST';

    return new fetch_api(page, data, init);
    
  }

  static put(page, data, init) {
    init.method = 'PUT';

    return new fetch_api(page, data, init);
    
  }

  static delete(page, data, init) {
    init.method = 'DELETE';

    return new fetch_api(page, data, init);
    
  }

}