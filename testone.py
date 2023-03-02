from tkinter import *
from tkinter import filedialog

root=Tk()
root.geometry("310X163")

def encrypt_image():
    file1=filedialog.askopenfile(mode='r',filetype=[('jpg file', '*.jpg')])
    if file is not None:
        #print(file1)
        file_name=file1.name
        #print(file_name)
        key=entry1.get(1.0,END)
        print(file_name,key)

b1=Button(root,text="encrypt", command=encrypt_image)
b1.place(x=70,y=10)

entry1=Text(root,height=1,width=10)
entry1.place(x=50,y=50)

root.mainloop()
 