from flask import Flask, request

app = Flask(__name__)

@app.route('/search')
def search():
    search_query = request.args.get('q')
    if search_query == 'apple':
        return 'Apple is a fruit'
    elif search_query == 'banana':
        return 'Banana is a fruit'
    else:
        return 'No results found'

if __name__ == '__main__':
    app.run(debug=False)